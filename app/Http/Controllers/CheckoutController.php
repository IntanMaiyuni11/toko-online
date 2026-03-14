<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\User; // Digunakan untuk type-hinting
use App\Models\Transaction;
use App\Models\TransactionDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        /** @var User $user */
        $user = Auth::user(); // Menambahkan type-hint agar 'update' terbaca
        
        // Update data user kecuali total_price
        $user->update($request->except('total_price'));

        // Ambil data keranjang terbaru
        $carts = Cart::with(['product'])
                    ->where('users_id', $user->id)
                    ->get();

        // Validasi Stok berdasarkan Quantity yang dibeli
        foreach ($carts as $index => $cart) {
            $requestedQty = $request->quantities[$index] ?? 1;
            if ($cart->product->stock < $requestedQty) {
                return response()->json([
                    'error' => 'Stok produk ' . $cart->product->name . ' tidak mencukupi.'
                ], 422);
            }
        }

        $total_price = (int) $request->total_price;
        $code = 'STORE-' . mt_rand(0000, 9999);

        // Buat Transaksi Utama
        $transaction = Transaction::create([
            'users_id' => $user->id,
            'inscurance_price' => 100,
            'shipping_price' => 0,
            'total_price' => $total_price,
            'transaction_status' => 'PENDING',
            'code' => $code
        ]);

        // Buat Detail Transaksi & Potong Stok Sesuai Quantity
        foreach ($carts as $index => $cart) {
            $requestedQty = $request->quantities[$index] ?? 1;
            
            TransactionDetail::create([
                'transactions_id' => $transaction->id,
                'products_id' => $cart->product->id,
                'price' => $cart->product->price,
                'shipping_status' => 'PENDING',
                'resi' => '',
                'code' => 'TRX-' . mt_rand(0000, 9999)
            ]);

            // Potong stok sesuai jumlah yang dibeli
            $cart->product->decrement('stock', $requestedQty);
        }

        // Hapus Keranjang setelah checkout
        Cart::where('users_id', $user->id)->delete();

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        $midtrans = [
            'transaction_details' => [
                'order_id' => $code,
                'gross_amount' => $total_price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'enabled_payments' => ['gopay', 'bank_transfer', 'shopeepay', 'qris'],
        ];

        try {
            $snapToken = Snap::getSnapToken($midtrans);
            return response()->json(['snap_token' => $snapToken]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function direct(Request $request)
    {
        Cart::create([
            'products_id' => $request->products_id,
            'users_id' => Auth::id()
        ]);

        return redirect()->route('cart');
    }

    public function callback(Request $request)
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        
        try {
            $notification = new Notification();
            $order_id = $notification->order_id;
            $status = $notification->transaction_status;

            $transaction = Transaction::where('code', $order_id)->first();

            if ($transaction) {
                if ($status == 'settlement' || $status == 'capture') {
                    $transaction->transaction_status = 'SUCCESS';
                } else if ($status == 'pending') {
                    $transaction->transaction_status = 'PENDING';
                } else if (in_array($status, ['deny', 'expire', 'cancel'])) {
                    $transaction->transaction_status = 'CANCELLED';
                }

                $transaction->save();
                return response()->json(['message' => 'Status Berhasil Diupdate'], 200);
            }

            return response()->json(['message' => 'Transaksi Tidak Ditemukan'], 404);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}