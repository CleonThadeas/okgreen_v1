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
use App\Http\Controllers\Admin\WasteManagementController as AdminWasteCtrl;
use App\Http\Controllers\Staff\WasteManagementController as StaffWasteCtrl;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IframeController;

Route::get('/', function () {
    return view('LandingPage');
});

// ================== LOGIN & LOGOUT ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [MultiGuardLoginController::class, 'create'])->name('login');
    Route::post('/login', [MultiGuardLoginController::class, 'store']);
});

// Protect logout for any of the guards (will try admin, staff, web)
Route::post('/logout', [MultiGuardLoginController::class, 'destroy'])
    ->middleware('auth:admin,staff,web')
    ->name('logout');

// ================== DASHBOARD PER GUARD ==================
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth:staff'])->get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

// Redirect route dari dashboard ke halaman pembelian (supaya route('dashboard.buy') valid)
Route::middleware('auth:web')->get('/dashboard/buy', function () {
    return redirect()->route('buy-waste.index');
})->name('dashboard.buy');

// ================== USER PROFILE ==================
Route::middleware(['auth:web'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ================== USER (web guard) - Pembelian Sampah ==================
// User (explicit web guard)
// Route::middleware('auth:web')->group(function()//
    Route::get('/buy-waste', [WasteController::class, 'index'])->name('buy-waste.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // tambahan
    Route::view('/sell-waste', 'user.sell.index')->name('sell-waste.index');
    Route::view('/edu', 'user.edukasi.index')->name('edu.index');
    Route::post('/checkout/prepare', [CheckoutController::class, 'prepare'])->name('checkout.prepare');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.form');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');


// ================== STAFF ==================
// Staff routes (manage wastes)
Route::prefix('staff')->name('staff.')->middleware('auth:staff')->group(function(){
    Route::get('/wastes', [App\Http\Controllers\Staff\WasteManagementController::class, 'index'])->name('wastes.index');

    Route::get('/wastes/category/create', [App\Http\Controllers\Staff\WasteManagementController::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [App\Http\Controllers\Staff\WasteManagementController::class, 'storeCategory'])->name('wastes.category.store');

    Route::get('/wastes/type/create', [App\Http\Controllers\Staff\WasteManagementController::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [App\Http\Controllers\Staff\WasteManagementController::class, 'storeType'])->name('wastes.type.store');

    // optional: add stock (if you want a separate form)
    Route::post('/wastes/stock', [App\Http\Controllers\Staff\WasteManagementController::class, 'addStock'])->name('wastes.stock.add');
// STAFF (dalam group prefix('staff')->name('staff.')->middleware('auth:staff')->group(...) )
Route::get('/wastes/type/{id}/edit', [App\Http\Controllers\Staff\WasteManagementController::class, 'editType'])->name('wastes.type.edit');
Route::put('/wastes/type/{id}', [App\Http\Controllers\Staff\WasteManagementController::class, 'updateType'])->name('wastes.type.update');

    Route::get('/sell-requests', [\App\Http\Controllers\Staff\SellRequestController::class, 'index'])
        ->name('sell_requests.index');
});

// ================== ADMIN ==================
// Admin routes (manage wastes + staff later)
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function(){
    Route::get('/wastes', [App\Http\Controllers\Admin\WasteManagementController::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [App\Http\Controllers\Admin\WasteManagementController::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [App\Http\Controllers\Admin\WasteManagementController::class, 'storeCategory'])->name('wastes.category.store');

    Route::get('/wastes/type/create', [App\Http\Controllers\Admin\WasteManagementController::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [App\Http\Controllers\Admin\WasteManagementController::class, 'storeType'])->name('wastes.type.store');

    Route::post('/wastes/stock', [App\Http\Controllers\Admin\WasteManagementController::class, 'addStock'])->name('wastes.stock.add');
// ADMIN (dalam group prefix('admin')->name('admin.')->middleware('auth:admin')->group(...) )
Route::get('/wastes/type/{id}/edit', [App\Http\Controllers\Admin\WasteManagementController::class, 'editType'])->name('wastes.type.edit');
Route::put('/wastes/type/{id}', [App\Http\Controllers\Admin\WasteManagementController::class, 'updateType'])->name('wastes.type.update');
    // Admin - Manage Staff (Users)
Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

});

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
Route::get('/register', function () {
    return view('RegisterPage');
})->name('register');

Route::get('/stokadmin', function () {
    return view('stokadmin');
})->name('stokadmin');

Route::get('/stokform', function () {
    return view('stok-form');
})->name('stokform');

Route::get('/berandadmin', function () {
    return view('berandadmin');
})->name('berandadmin');

Route::get('/detailadmin', function () {
    return view('detail-admin');
})->name('detailadmin');

Route::get('/detailpengguna', function () {
    return view('detail-pengguna');
})->name('detailpengguna');

Route::get('/tambahadmin' , function () {
    return view('form-tambah-admin');
})->name('tambahadmin');

Route::get('/listorderan', function () {
    return view('list-orderan');
})->name('listorderan');

Route::get('/stoksampah', function (){
    return view('stoksampah');
})->name('stoksampah');

Route::get('/banyaksampah', function (){
    return view('banyak-sampah');
})->name('banyaksampah');

Route::get('/beranda', function () {
    return view('beranda'); 
})->name('beranda');

Route::get('/jual-barang', function () {
    return view('jualbarang');
})->name('jual-barang');

Route::get('/belibarang', function () {
    return view('belibarang');
})->name('belibarang');

Route::get('/detail-produk/{id}', function ($id) {
    return view('detailbarang', ['id' => $id]);
})->name('detail-barang');

Route::get('/co-detail', function () {
    return view('co-detail');
});

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/payment', function () {
    return view('payment');
});

Route::get('/detail_payment', function () {
    return view('detail_payment');
})->name('detail_payment');

Route::get('/notifikasi', function () {
    return view('notifikasi');
})->name('notifikasi');

Route::get('/profil', function () {
    return view('profil');
})->name('profil');

route::get('/berandavoucher', function () {
    return view('berandavoucher');
})->name('berandavoucher');

route:: get('/tukarvoucher', function () {
    return view('tukarvoucher');
})->name('tukarvoucher');

route:: get('/profileadmin', function () {
    return view('profileadmin');
})->name('profileadmin');

route:: get('/detailstokstaff', function () {
    return view('detailstokstaff');
})->name('detailstokstaff');
