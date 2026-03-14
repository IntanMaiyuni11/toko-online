<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Product; 
use App\Models\ProductReview; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // --- LOGIKA UNTUK CUSTOMER ---
    if ($user->roles == 'CUSTOMER') {
        $transactions = TransactionDetail::whereHas('transaction', function($t) use ($user) {
            $t->where('users_id', $user->id);
        });

        return view('pages.dashboard', [
            'transaction_count' => (clone $transactions)->count(),
            'pending_count'     => (clone $transactions)->where('shipping_status', 'PENDING')->count(),
            'shipping_count'    => (clone $transactions)->where('shipping_status', 'SHIPPING')->count(),
            'completed_count'   => (clone $transactions)->where('shipping_status', 'SUCCESS')->count(),
            'transaction_data'  => (clone $transactions)->with(['product.galleries', 'transaction.user'])->latest()->take(5)->get(),
        ]);
    } 
    
    // --- LOGIKA UNTUK SELLER CENTER ---
    $sellerTransactions = TransactionDetail::whereHas('product', function($p) use ($user) {
        $p->where('users_id', $user->id);
    });

    // 1. Ambil data Chart (7 Hari Terakhir)
    $days = [];
    $chart_data = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i)->format('Y-m-d');
        $days[] = Carbon::now()->subDays($i)->isoFormat('ddd'); // Nama hari (Sen, Sel, dst)
        
        $count = (clone $sellerTransactions)
                    ->whereDate('created_at', $date)
                    ->count();
        $chart_data[] = $count;
    }

    // 2. Ambil Ulasan Baru (24 jam terakhir)
    $new_reviews = ProductReview::whereHas('product', function($p) use ($user) {
        $p->where('users_id', $user->id);
    })->where('created_at', '>=', Carbon::now()->subDay())->count();

    return view('pages.dashboard', [
        // Statistik Utama
        'revenue'           => (clone $sellerTransactions)->where('shipping_status', 'SUCCESS')->sum('price'),
        'transaction_count' => (clone $sellerTransactions)->count(),
        'customer'          => User::where('roles', 'CUSTOMER')->count(),
        
        // Bagian "Penting Hari Ini"
        'new_orders'        => (clone $sellerTransactions)->where('shipping_status', 'PENDING')->count(),
        'ready_to_ship'     => (clone $sellerTransactions)->where('shipping_status', 'SHIPPING')->count(),
        'new_reviews'       => $new_reviews,
        
        // Data List & Chart
        'transaction_data'  => (clone $sellerTransactions)->with(['transaction.user', 'product.galleries'])->latest()->take(5)->get(),
        'chart_data'        => $chart_data,
        'chart_labels'      => $days,
    ]);
}

    public function orders(Request $request)
    {
        $user = Auth::user();
        // Menggunakan query() sebagai pengganti get() agar tidak deprecated
        $status = $request->query('status', 'ALL');
        $dateRange = $request->query('date_range');
        $keyword = $request->query('keyword');

        $query = TransactionDetail::with(['transaction.user', 'product.galleries', 'product.category'])
            ->whereHas('product', function($product) use ($user) {
                $product->where('users_id', $user->id);
            });

        if ($status !== 'ALL') {
            $query->where('shipping_status', $status);
        }

        if ($keyword) {
            $query->whereHas('transaction', function($q) use ($keyword) {
                $q->where('code', 'like', "%$keyword%");
            });
        }

        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if(count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        return view('pages.dashboard-orders', [
            'order_data' => $query->latest()->paginate(5),
            'activeStatus' => $status,
            'selectedDate' => $dateRange
        ]);
    }

    public function myOrders(Request $request)
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
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        return view('pages.dashboard-orders-customer', [
            'order_data' => $query->latest()->get(),
            'activeStatus' => $status,
            'selectedDate' => $dateRange
        ]);
    }

    public function promotions() 
    {
        $user = Auth::user();
        $rewards = \App\Models\Reward::latest()->get();
        $histories = \App\Models\PointHistory::where('users_id', $user->id)->latest()->get();

        return view('pages.rewards', [
            'rewards' => $rewards,
            'histories' => $histories
        ]);
    }

   public function logistics() 
    {
        // Pastikan hanya Seller (USER) atau ADMIN yang bisa akses
        // Jika Customer mencoba masuk, lempar balik ke dashboard
        if(Auth::user()->roles == 'CUSTOMER') {
            return redirect()->route('dashboard');
        }

        return view('pages.dashboard-logistics');
    }

    public function logisticsUpdate(Request $request)
    {
        /** @var \App\Models\User $user */ 
        $user = Auth::user(); 
        
        $user->is_jt = $request->has('kurir_jt');
        $user->is_sicepat = $request->has('kurir_sicepat');
        $user->is_pos = $request->has('kurir_pos');
        $user->save(); 

        return redirect()->route('dashboard-logistics')->with('success', 'Pengaturan logistik berhasil diperbarui!');
    }

    public function performance()
{
    $user = Auth::user();

    // 1. Hitung Rata-rata Rating
    $averageRating = ProductReview::whereHas('product', function($product) use ($user) {
        $product->where('users_id', $user->id);
    })->avg('rating');

    // 2. Hitung Total Transaksi & Tingkat Keberhasilan
    $totalTransactions = TransactionDetail::whereHas('product', function($product) use ($user) {
        $product->where('users_id', $user->id);
    })->count();
                                     
    $successTransactions = TransactionDetail::whereHas('product', function($product) use ($user) {
        $product->where('users_id', $user->id);
    })->where('shipping_status', 'SUCCESS')->count();

    $performanceRate = $totalTransactions > 0 ? ($successTransactions / $totalTransactions) * 100 : 0;

    // 3. Tambahkan Total Ulasan (Data Baru)
    $totalReviews = ProductReview::whereHas('product', function($product) use ($user) {
        $product->where('users_id', $user->id);
    })->count();

    // 4. Ambil 5 Ulasan Terbaru untuk Tabel (Data Baru)
    $reviews = ProductReview::with(['product', 'user'])
        ->whereHas('product', function($product) use ($user) {
            $product->where('users_id', $user->id);
        })
        ->latest()
        ->take(5)
        ->get();

    return view('pages.dashboard-performance', [
        'averageRating'   => number_format($averageRating, 1),
        'performanceRate' => round($performanceRate),
        'totalReviews'    => $totalReviews, // Kirim ke Blade
        'reviews'         => $reviews,      // Kirim ke Blade
    ]);
}

    public function reviews()
{
    $user = Auth::user();

    if ($user->roles == 'USER' || $user->roles == 'ADMIN') {
        // Logika untuk Seller (Tetap sama seperti kode kamu)
        $product_stats = Product::where('users_id', $user->id)
            ->withCount('reviews as total_reviews')
            ->withAvg('reviews as avg_rating', 'rating')
            ->get();

        $reviews = ProductReview::with(['product', 'user'])
            ->whereHas('product', function($product) use ($user) {
                $product->where('users_id', $user->id);
            })->latest()->paginate(10);

        $store_performance = [
            'penalty_points' => 0,
            'reward_badge' => 'Top Seller Candidate',
            'response_rate' => '98%'
        ];

        return view('pages.dashboard-reviews', [
            'product_stats' => $product_stats,
            'reviews' => $reviews,
            'performance' => $store_performance
        ]);
    } 
    else {
        // LOGIKA UNTUK CUSTOMER (Perbaikan di sini)
        
        // 1. Ambil riwayat ulasan yang PERNAH dibuat oleh Customer ini
        $my_reviews = ProductReview::with(['product.galleries'])
            ->where('users_id', $user->id)
            ->latest()
            ->get();
            
        // 2. Ambil produk dari transaksi yang sudah SUCCESS tapi BELUM diulas
        $pending_reviews = TransactionDetail::with(['transaction', 'product.galleries'])
            ->whereHas('transaction', function($t) use ($user) {
                $t->where('users_id', $user->id);
            })
            ->where('shipping_status', 'SUCCESS')
            ->whereDoesntHave('product.reviews', function($query) use ($user) {
                $query->where('users_id', $user->id);
            })->get();

        return view('pages.dashboard-reviews', [
            'my_reviews' => $my_reviews, // Variabel ini yang tadinya hilang
            'pending_reviews' => $pending_reviews
        ]);
    }
}

   public function statistics(Request $request)
{
    $user = Auth::user();
    $selectedYear = $request->query('year', date('Y'));
    $availableYears = [date('Y'), date('Y') - 1, date('Y') - 2];

    // 1. Definisikan Labels untuk nama bulan
    $labels = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // 2. Siapkan template data 12 bulan (inisialisasi 0)
    $monthlyRevenue = collect(range(1, 12))->mapWithKeys(function ($month) {
        return [$month => 0];
    });

    // 3. Ambil data pendapatan asli dari database
    $revenueData = TransactionDetail::whereHas('product', function($product) use ($user) {
            $product->where('users_id', $user->id);
        })
        ->whereHas('transaction', function($transaction) {
            $transaction->where('transaction_status', 'SUCCESS');
        })
        ->whereYear('created_at', $selectedYear)
        ->selectRaw('MONTH(created_at) as month, SUM(price) as total')
        ->groupBy('month')
        ->pluck('total', 'month');

    // 4. Gabungkan dan ambil nilainya saja (format array untuk Chart.js)
    $data = $monthlyRevenue->replace($revenueData)->values();

    // 5. Data statistik lainnya
    $product_stats = Product::where('users_id', $user->id)
        ->withCount(['reviews as total_reviews'])
        ->withAvg('reviews as avg_rating', 'rating')
        ->get();

    $pending_reviews = TransactionDetail::with(['transaction', 'product'])
        ->whereHas('transaction', function($transaction) use ($user) {
            $transaction->where('users_id', $user->id);
        })
        ->where('shipping_status', 'SUCCESS')
        ->get();

    // 6. Kirim SEMUA variabel yang diminta Blade
    return view('pages.dashboard-statistics', [
        'product_stats' => $product_stats,
        'pending_reviews' => $pending_reviews,
        'selectedYear' => $selectedYear,
        'availableYears' => $availableYears,
        'data' => $data,
        'labels' => $labels // Variabel ini yang memperbaiki error baris 83
    ]);
}
public function education()
{
    $articles = [
        [
            'title' => 'Tips Foto Produk Menarik',
            'category' => 'Marketing',
            'desc' => 'Gunakan pencahayaan alami dan background polos untuk meningkatkan klik pembeli.',
            'icon' => 'fa-camera' // Pastikan key 'icon' ini ada
        ],
        [
            'title' => 'Cara Memproses Pesanan',
            'category' => 'Operasional',
            'desc' => 'Langkah demi langkah mulai dari konfirmasi hingga cetak resi pengiriman.',
            'icon' => 'fa-box-open' // Pastikan key 'icon' ini ada
        ],
        [
            'title' => 'Aturan Komunitas Penjual',
            'category' => 'Kebijakan',
            'desc' => 'Pahami hal-hal yang dilarang agar tokomu tidak terkena pinalti atau banned.',
            'icon' => 'fa-gavel' // Pastikan key 'icon' ini ada
        ],
    ];

    return view('pages.dashboard-education', compact('articles'));
}
}