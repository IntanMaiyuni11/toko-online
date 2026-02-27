<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\PointHistory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // 1. Simpan/Update data user (alamat, hp, dll)
        $user = User::findOrFail(Auth::user()->id);
        $user->update($request->except('total_price'));

        // 2. Proses pembuatan kode transaksi
        $code = 'STORE-' . mt_rand(0000, 9999);
        $carts = Cart::with(['product', 'user'])
                    ->where('users_id', Auth::user()->id)
                    ->get();

        // 3. Buat Transaksi Utama
        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'inscurance_price' => 5,
            'shipping_price' => 0,
            'total_price' => (int) $request->total_price,
            'transaction_status' => 'PENDING',
            'code' => $code
        ]);

        // 4. Buat Detail Transaksi (Per Item)
        foreach ($carts as $cart) {
            $trx = 'TRX-' . mt_rand(0000, 9999);

            TransactionDetail::create([
                'transactions_id' => $transaction->id,
                'products_id' => $cart->product->id,
                'price' => $cart->product->price,
                'shipping_status' => 'PENDING',
                'resi' => '',
                'code' => $trx
            ]);
        }

        // 5. Hapus data keranjang setelah checkout
        Cart::where('users_id', Auth::user()->id)->delete();

        // 6. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // 7. Siapkan parameter Midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $code,
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'enabled_payments' => ['gopay', 'bank_transfer', 'shopeepay'],
            'vtweb' => []
        ];

        try {
            // Ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            
            // Redirect ke Halaman Pembayaran Midtrans
            return redirect($paymentUrl);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        // 1. Set konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // 2. Instance notification
        $notification = new Notification();

        // 3. Cari transaksi berdasarkan kode (order_id)
        $transaction = Transaction::where('code', $notification->order_id)->firstOrFail();

        // 4. Ambil status dari Midtrans
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;

        // 5. Simpan status lama sebelum diupdate (untuk pengecekan poin)
        $oldStatus = $transaction->transaction_status;

        // 6. Logika penentuan status transaksi
        if ($status == 'capture') {
            if ($type == 'credit_card') {
                $transaction->transaction_status = ($fraud == 'challenge') ? 'PENDING' : 'SUCCESS';
            }
        } else if ($status == 'settlement') {
            $transaction->transaction_status = 'SUCCESS';
        } else if ($status == 'pending') {
            $transaction->transaction_status = 'PENDING';
        } else if (in_array($status, ['deny', 'expire', 'cancel'])) {
            $transaction->transaction_status = 'CANCELLED';
        }

        $transaction->save();

        // 7. LOGIKA PENAMBAHAN POIN + CATAT RIWAYAT
        // Syarat: Status sekarang SUCCESS dan sebelumnya BUKAN SUCCESS
        if ($transaction->transaction_status == 'SUCCESS' && $oldStatus != 'SUCCESS') {
            $user = User::find($transaction->users_id);
            
            if($user) {
                // Hitung poin (Contoh: Harga * 10)
                $earnedPoints = $transaction->total_price * 10;
                
                // Update total poin user
                $user->points += $earnedPoints;
                $user->save();

                // Catat ke tabel PointHistory
                PointHistory::create([
                    'users_id' => $user->id,
                    'amount' => $earnedPoints,
                    'description' => 'Poin dari transaksi ' . $transaction->code
                ]);
            }
        }

        return response()->json([
            'meta' => [
                'code' => 200,
                'message' => 'Midtrans Notification Success'
            ]
        ]);
    }
}