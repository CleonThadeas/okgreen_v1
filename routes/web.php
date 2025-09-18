<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Auth\MultiGuardLoginController;
use App\Http\Controllers\WasteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AddressController;

// ================== LANDING PAGE ==================
Route::get('/', function () {
    return view('LandingPage'); // Guest akan masuk ke LandingPage
})->name('home');

// ================== LOGIN & LOGOUT ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [MultiGuardLoginController::class, 'create'])->name('login');
    Route::post('/login', [MultiGuardLoginController::class, 'store']);
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [MultiGuardLoginController::class, 'destroy'])
    ->middleware('auth:admin,staff,web')
    ->name('logout');

// ================== DASHBOARD PER GUARD ==================
Route::middleware(['auth:web'])->get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth:staff'])->get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

// ================== USER PROFILE ==================
Route::middleware(['auth:web'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ================== USER (web guard) - Pembelian Sampah ==================
Route::middleware('auth:web')->group(function () {
    Route::get('/buy-waste', [WasteController::class, 'index'])->name('buy-waste.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::view('/sell-waste', 'user.sell.index')->name('sell-waste.index');
    Route::view('/edu', 'user.edukasi.index')->name('edu.index');

    // Checkout
    Route::post('/checkout/prepare', [CheckoutController::class, 'prepare'])->name('checkout.prepare');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.form');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/remove', [CheckoutController::class, 'remove'])->name('checkout.remove'); // ✅ ditambahkan di sini
});

// ================== PRODUK ==================
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/detail-barang/{id}', [ProductController::class, 'detailBarang'])->name('detail-barang');

// ================== STAFF ==================
Route::prefix('staff')->name('staff.')->middleware('auth:staff')->group(function () {
    Route::get('/wastes', [\App\Http\Controllers\Staff\WasteManagementController::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [\App\Http\Controllers\Staff\WasteManagementController::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [\App\Http\Controllers\Staff\WasteManagementController::class, 'storeCategory'])->name('wastes.category.store');

    Route::get('/wastes/type/create', [\App\Http\Controllers\Staff\WasteManagementController::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [\App\Http\Controllers\Staff\WasteManagementController::class, 'storeType'])->name('wastes.type.store');

    Route::post('/wastes/stock', [\App\Http\Controllers\Staff\WasteManagementController::class, 'addStock'])->name('wastes.stock.add');
    Route::get('/wastes/type/{id}/edit', [\App\Http\Controllers\Staff\WasteManagementController::class, 'editType'])->name('wastes.type.edit');
    Route::put('/wastes/type/{id}', [\App\Http\Controllers\Staff\WasteManagementController::class, 'updateType'])->name('wastes.type.update');

    Route::get('/sell-requests', [\App\Http\Controllers\Staff\SellRequestController::class, 'index'])->name('sell_requests.index');
});

// ================== ADMIN ==================
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/wastes', [\App\Http\Controllers\Admin\WasteManagementController::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [\App\Http\Controllers\Admin\WasteManagementController::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [\App\Http\Controllers\Admin\WasteManagementController::class, 'storeCategory'])->name('wastes.category.store');

    Route::get('/wastes/type/create', [\App\Http\Controllers\Admin\WasteManagementController::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [\App\Http\Controllers\Admin\WasteManagementController::class, 'storeType'])->name('wastes.type.store');

    Route::post('/wastes/stock', [\App\Http\Controllers\Admin\WasteManagementController::class, 'addStock'])->name('wastes.stock.add');
    Route::get('/wastes/type/{id}/edit', [\App\Http\Controllers\Admin\WasteManagementController::class, 'editType'])->name('wastes.type.edit');
    Route::put('/wastes/type/{id}', [\App\Http\Controllers\Admin\WasteManagementController::class, 'updateType'])->name('wastes.type.update');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});

// ================== ADDRESS ==================
Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');

// ================== DEBUG SESSION (HANYA LOKAL) ==================
Route::get('/debug-session', function () {
    if (!app()->isLocal()) {
        abort(404);
    }
    return response()->json([
        'is_web_logged_in' => Auth::guard('web')->check(),
        'is_admin_logged_in' => Auth::guard('admin')->check(),
        'is_staff_logged_in' => Auth::guard('staff')->check(),
        'web_user' => Auth::guard('web')->user(),
        'admin_user' => Auth::guard('admin')->user(),
        'staff_user' => Auth::guard('staff')->user(),
        'session_id' => Session::getId(),
        'all_session_data' => Session::all(),
        'cookie_session' => request()->cookie(config('session.cookie')),
    ]);
});
