<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardTransactionController extends Controller
{
    // 1. DASHBOARD UTAMA
    public function index()
    {
        $user = Auth::user();

        $sellTransactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
            ->whereHas('product', function ($product) use ($user) {
                $product->where('users_id', $user->id);
            })->latest()->get();

        $buyTransactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
            ->whereHas('transaction', function ($transaction) use ($user) {
                $transaction->where('users_id', $user->id);
            })->latest()->get();

        return view('pages.dashboard-transactions', [
            'sellTransactions' => $sellTransactions,
            'buyTransactions' => $buyTransactions,
        ]);
    }

    // 2. PESANAN MASUK (Penjual)
public function orders(Request $request)
{
    $user = Auth::user();
    $status = $request->query('status', 'ALL');
    $keyword = $request->query('keyword');
    $dateRange = $request->query('date_range');

    $query = TransactionDetail::with(['transaction.user', 'product.galleries', 'product.category'])                
        ->whereHas('product', function ($product) use ($user) {
            $product->where('users_id', $user->id);
        });

    if ($status !== 'ALL') {
        $query->where('shipping_status', $status);
    }

    if ($keyword) {
        $query->where(function($mainQuery) use ($keyword) {
            $mainQuery->whereHas('transaction', function($q) use ($keyword) {
                $q->where('code', 'LIKE', '%' . $keyword . '%');
            })->orWhereHas('product', function($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%');
            });
        });
    }

    if ($dateRange) {
        $dates = explode(' - ', $dateRange);
        if(count($dates) == 2) {
            $start = \Carbon\Carbon::parse($dates[0])->startOfDay();
            $end = \Carbon\Carbon::parse($dates[1])->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }
    }

    // Mengelompokkan berdasarkan transaksi agar 1 invoice tidak dobel card
    $order_data = $query->latest()->get()->groupBy('transactions_id');

    return view('pages.dashboard-orders', [
        'order_groups' => $order_data, // Ubah variabel agar lebih jelas
        'activeStatus' => $status
    ]);
}

    // 3. PESANAN SAYA (Pembeli)
    public function myShopping(Request $request)
{
    $user = Auth::user();
    $status = $request->query('status', 'ALL');
    $keyword = $request->query('keyword');
    $dateRange = $request->query('date_range');

    $query = TransactionDetail::with(['transaction', 'product.galleries', 'product.user'])
        ->whereHas('transaction', function($transaction) use ($user) {
            $transaction->where('users_id', $user->id);
        });

    if ($status !== 'ALL') {
        $query->where('shipping_status', $status);
    }

    if ($keyword) {
        $query->where(function($q) use ($keyword) {
            $q->whereHas('transaction', function($t) use ($keyword) {
                $t->where('code', 'like', "%$keyword%");
            })->orWhereHas('product', function($p) use ($keyword) {
                $p->where('name', 'like', "%$keyword%");
            });
        });
    }

    if ($dateRange) {
        $dates = explode(' - ', $dateRange);
        if(count($dates) == 2) {
            $start = \Carbon\Carbon::parse($dates[0])->startOfDay();
            $end = \Carbon\Carbon::parse($dates[1])->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }
    }

    // Kelompokkan data berdasarkan transactions_id
    $order_data = $query->latest()->get()->groupBy('transactions_id');

    return view('pages.dashboard-orders-customer', [
        'order_groups' => $order_data, // Ini yang dibaca oleh @forelse di Blade
        'activeStatus' => $status,
        'selectedDate' => $dateRange
    ]);
}

    // 4. HALAMAN DETAIL
   public function details(Request $request, $id)
{
    // Cari data detail yang diklik
    $item = TransactionDetail::with(['transaction.user', 'product.galleries', 'product.user'])
        ->findOrFail($id);

    // Ambil SEMUA produk dalam transaksi yang sama
    $all_products = TransactionDetail::with(['product.galleries'])
        ->where('transactions_id', $item->transactions_id)
        ->get();

    return view('pages.dashboard-transactions-details', [
        'transaction' => $item,          // Data utama (alamat, user, dll)
        'all_products' => $all_products  // Daftar semua produk untuk dilooping
    ]);
}

    // 5. UPDATE STATUS & POIN OTOMATIS
   public function update(Request $request, $id)
{
    $item = TransactionDetail::with(['transaction.user', 'product'])->findOrFail($id);
    
    // Cek akses: Pemilik toko
    if($item->product->users_id == Auth::user()->id || $request->shipping_status == 'CANCELLED') {
        
        $oldStatus = $item->shipping_status;

        DB::transaction(function () use ($item, $request, $oldStatus) {
            
            // 1. UPDATE SEMUA PRODUK DALAM TRANSAKSI INI
            // Ini memastikan jika ada 2-3 produk, semuanya berubah status & resi sekaligus
            TransactionDetail::where('transactions_id', $item->transactions_id)
                ->whereHas('product', function($q) {
                    $q->where('users_id', Auth::user()->id);
                })
                ->update([
                    'shipping_status' => $request->shipping_status,
                    'resi'            => $request->resi ?? $item->resi,
                    'code'            => $request->code ?? $item->code, 
                ]);

            // 2. LOGIKA POIN (Hanya jika status berubah ke SUCCESS)
            if ($request->shipping_status == 'SUCCESS' && $oldStatus != 'SUCCESS') {
                $user = $item->transaction->user;

                if ($user) {
                    // Ambil kembali semua produk untuk menghitung total poin
                    $details = TransactionDetail::where('transactions_id', $item->transactions_id)->get();
                    
                    foreach ($details as $detail) {
                        $earnedPoints = floor($detail->price * 0.01);
                        if ($earnedPoints > 0) {
                            $user->increment('points', $earnedPoints);
                            PointHistory::create([
                                'users_id' => $user->id,
                                'amount' => $earnedPoints,
                                'description' => 'Poin belanja: ' . $detail->product->name . ' (Inv: ' . $item->transaction->code . ')'
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('dashboard-transaction-details', $id)
                         ->with('success', 'Seluruh produk dalam pesanan ini telah diperbarui.');
    }

    return redirect()->route('dashboard-transaction-details', $id)
                     ->with('error', 'Anda tidak memiliki akses');
}

    // 6. DETAIL AJAX
    public function getDetailsAjax($id)
    {
        $transaction = TransactionDetail::with(['transaction.user', 'product.galleries', 'product.user'])
            ->findOrFail($id);

        return response()->json([
            'status' => str_replace('_', ' ', $transaction->shipping_status),
            'invoice' => $transaction->transaction->code,
            'date' => $transaction->created_at->format('d F Y, H:i'),
            'store_name' => $transaction->product->user->store_name,
            'product_name' => $transaction->product->name,
            'price' => number_format($transaction->price, 0, ',', '.'),
            'image' => $transaction->product->galleries->first() ? Storage::url($transaction->product->galleries->first()->photos) : '/images/default.jpg',
            'customer_name' => $transaction->transaction->user->name,
            'phone' => $transaction->transaction->user->phone_number,
            'address' => $transaction->transaction->user->address_one,
            'resi' => $transaction->resi ?? 'Belum ada resi',
            'kurir' => $transaction->code ?? '-',
        ]);
    }
}