<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProductDiscussionController;

// USER DASHBOARD
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\DashboardSettingController;
use App\Http\Controllers\DashboardTransactionController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ChatController;

// ADMIN DASHBOARD
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductGalleryController as AdminProductGalleryController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/categories/{id}', [CategoryController::class, 'detail'])->name('categories-detail');
Route::get('/details/{id}', [DetailController::class, 'index'])->name('detail');
Route::post('/details/{id}', [DetailController::class, 'add'])->name('detail-add');
Route::post('/details/review/{id}', [DetailController::class, 'review'])->name('detail-review');
Route::post('/details/{id}/discussion', [ProductDiscussionController::class, 'store'])->name('product-discussion-store');
Route::get('/success', [CartController::class, 'success'])->name('success');

/*
|--------------------------------------------------------------------------
| REGISTER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('register/check', [RegisteredUserController::class, 'check'])->name('api-register-check');
});
Route::get('register/success', [RegisteredUserController::class, 'success'])->name('register-success');

/*
|--------------------------------------------------------------------------
| USER DASHBOARD (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {

    // --- 1. AKSES UMUM (BISA DIAKSES PEMBELI & PENJUAL) ---
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Fitur Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('dashboard-chat');
    Route::post('/chat', [ChatController::class, 'store'])->name('dashboard-chat-send');

    // Keranjang & Checkout
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart-delete');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout');
    Route::post('/checkout/direct', [CheckoutController::class, 'direct'])->name('checkout-direct'); 

    // --- FITUR PESANAN SAYA (PEMBELI) ---
    Route::get('/my-orders', [DashboardTransactionController::class, 'myShopping'])->name('dashboard-my-orders');
    
    // RUTE AJAX UNTUK MODAL
    Route::get('/transactions/ajax/{id}', [DashboardTransactionController::class, 'getDetailsAjax'])
        ->name('dashboard-transaction-details-ajax');

    // Detail Transaksi
    Route::get('/transactions', [DashboardTransactionController::class, 'index'])->name('dashboard-transaction');
    Route::get('/transactions/{id}', [DashboardTransactionController::class, 'details'])->name('dashboard-transaction-details');
    
    // Pengaturan Akun
    Route::get('/account', [DashboardSettingController::class, 'account'])->name('dashboard-settings-account');
    Route::post('/account/{redirect}', [DashboardSettingController::class, 'update'])->name('dashboard-settings-redirect');

    // Reward System & Promosi (Ditaruh di sini agar Customer bisa akses view Rewards via link Promotions)
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards');
    Route::post('/rewards/redeem/{id}', [RewardController::class, 'redeem'])->name('rewards-redeem'); 

    // Vouchers 
    Route::get('/vouchers', [App\Http\Controllers\RewardController::class, 'vouchers'])
    ->name('dashboard-vouchers');
    

    // --- 2. AKSES KHUSUS SELLER (IS SELLER) ---
    Route::middleware(['seller'])->group(function() {
        // Management Produk
        Route::get('/products', [DashboardProductController::class, 'index'])->name('dashboard-product');
        Route::get('/products/create', [DashboardProductController::class, 'create'])->name('dashboard-product-create');
        Route::post('/products', [DashboardProductController::class, 'store'])->name('dashboard-product-store');
        Route::get('/products/{id}', [DashboardProductController::class, 'details'])->name('dashboard-product-details');
        Route::post('/products/{id}', [DashboardProductController::class, 'update'])->name('dashboard-product-update');
        Route::post('/products/gallery/upload', [DashboardProductController::class, 'uploadGallery'])->name('dashboard-product-gallery-upload'); 
        Route::get('/products/gallery/delete/{id}', [DashboardProductController::class, 'deleteGallery'])->name('dashboard-product-gallery-delete'); 
        Route::get('/rewards/create', [RewardController::class, 'create'])->name('dashboard-rewards-create');
        Route::post('/rewards/store', [RewardController::class, 'store'])->name('dashboard-rewards-store');
        Route::get('/rewards/edit/{id}', [RewardController::class, 'edit'])->name('dashboard-rewards-edit');
        Route::post('/rewards/update/{id}', [RewardController::class, 'update'])->name('dashboard-rewards-update');
        Route::delete('/rewards/delete/{id}', [RewardController::class, 'destroy'])->name('dashboard-rewards-delete');
        Route::get('/promotions', [DashboardController::class, 'promotions'])->name('dashboard-promotions');

        // Manajemen Pesanan Masuk (Sisi Toko)
        Route::get('/orders', [DashboardTransactionController::class, 'orders'])->name('dashboard-orders');
        Route::post('/transactions-update/{id}', [DashboardTransactionController::class, 'update'])->name('dashboard-transaction-update');

        // Pengaturan Toko
        Route::get('/settings', [DashboardSettingController::class, 'store'])->name('dashboard-settings-store');
        
        // Dashboard Bisnis Seller (Eksklusif Seller)
        Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard-statistics');
        Route::get('/logistics', [DashboardController::class, 'logistics'])->name('dashboard-logistics');
        Route::post('/dashboard/logistics/update', [DashboardController::class, 'logisticsUpdate'])->name('dashboard-logistics-update');
        Route::get('/performance', [DashboardController::class, 'performance'])->name('dashboard-performance');

        // Ulasan
        Route::get('/reviews', [DashboardController::class, 'reviews'])->name('dashboard-reviews');
        Route::post('/reviews/store', [DashboardController::class, 'storeReview'])->name('dashboard-reviews-store');
        Route::post('/reviews/reply/{id}', [DashboardController::class, 'replyReview'])->name('dashboard-reviews-reply');

        // pusat edukasi 
        Route::get('/education', [DashboardController::class, 'education'])->name('dashboard-education');
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'is.admin']) 
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/category/restore/{id}', [AdminCategoryController::class, 'restore'])->name('category.restore');
        Route::resource('category', AdminCategoryController::class);
        Route::resource('user', AdminUserController::class);
        Route::resource('product', AdminProductController::class);
        Route::resource('productgallery', AdminProductGalleryController::class);
        Route::resource('transaction', AdminTransactionController::class);
        Route::resource('rewards', AdminRewardController::class);
    });

/*
|--------------------------------------------------------------------------
| PROFILE & AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';