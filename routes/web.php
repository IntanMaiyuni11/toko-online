<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\RegisteredUserController;

// USER DASHBOARD
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\DashboardSettingController;
use App\Http\Controllers\DashboardTransactionController;
use App\Http\Controllers\RewardController;

// ADMIN DASHBOARD (PAKAI ALIAS !!!)
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

Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories');
Route::get('/categories/{id}', [CategoryController::class, 'detail'])
    ->name('categories-detail');


Route::get('/details/{id}', [DetailController::class, 'index'])
    ->name('detail');
Route::post('/details/{id}', [DetailController::class, 'add'])
    ->name('detail-add');

Route::get('/success', [CartController::class, 'success'])
    ->name('success');

/*
|--------------------------------------------------------------------------
| Register 
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
    
    // Rute untuk pengecekan email (api-register-check)
    Route::get('register/check', [RegisteredUserController::class, 'check'])
                ->name('api-register-check');
});

Route::get('register/success', [RegisteredUserController::class, 'success'])
                ->name('register-success');

/*
|--------------------------------------------------------------------------
| USER DASHBOARD (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {

    Route::get('/cart', [CartController::class, 'index'])
    ->name('cart');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])
    ->name('cart-delete');

    Route::post('/checkout', [CheckoutController::class, 'process'])
    ->name('checkout');

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/products', [DashboardProductController::class, 'index'])
        ->name('dashboard-product');
    Route::get('/products/create', [DashboardProductController::class, 'create'])
        ->name('dashboard-product-create');
    Route::post('/products', [DashboardProductController::class, 'store'])
        ->name('dashboard-product-store');
    Route::get('/products/{id}', [DashboardProductController::class, 'details'])
        ->name('dashboard-product-details');
    Route::post('/products/{id}', [DashboardProductController::class, 'update'])
        ->name('dashboard-product-update');

    Route::post('/products/gallery/upload', [DashboardProductController::class, 'uploadGallery'])
        ->name('dashboard-product-gallery-upload'); 
    Route::get('/products/gallery/delete/{id}', [DashboardProductController::class, 'deleteGallery'])
        ->name('dashboard-product-gallery-delete'); 

    Route::get('/transactions', [DashboardTransactionController::class, 'index'])
        ->name('dashboard-transaction');
    Route::get('/transactions/{id}', [DashboardTransactionController::class, 'details'])
        ->name('dashboard-transaction-details');
    Route::post('/transactions/{id}', [DashboardTransactionController::class, 'update'])
        ->name('dashboard-transaction-update');

    Route::get('/settings', [DashboardSettingController::class, 'store'])
        ->name('dashboard-settings-store');
    Route::get('/account', [DashboardSettingController::class, 'account'])
        ->name('dashboard-settings-account');
    Route::post('/account/{redirect}', [DashboardSettingController::class, 'update'])
        ->name('dashboard-settings-redirect');

    Route::get('/rewards', [RewardController::class, 'index'])
        ->name('rewards');
    Route::post('/rewards/redeem/{id}', [RewardController::class, 'redeem'])
        ->name('rewards-redeem');
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
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
        // Tambahkan ini sebelum Route::resource
        Route::get('/category/restore/{id}', [AdminCategoryController::class, 'restore'])
            ->name('category.restore');

        Route::resource('category', AdminCategoryController::class);
        Route::resource('user', AdminUserController::class);
        Route::resource('product', AdminProductController::class);
        Route::resource('productgallery', AdminProductGalleryController::class);
        Route::resource('transaction', AdminTransactionController::class);
        Route::resource('rewards', AdminRewardController::class);
    });

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__.'/auth.php';