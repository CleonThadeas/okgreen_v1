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
use App\Http\Controllers\SellController;

// ================== LANDING PAGE ==================
Route::get('/', function () {
    return view('LandingPage');
})->name('home');

// ================== LOGIN & REGISTER ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [MultiGuardLoginController::class, 'create'])->name('login');
    Route::post('/login', [MultiGuardLoginController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// ================== LOGOUT ==================
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

// ================== USER (web guard) - BELI & JUAL ==================
Route::middleware('auth:web')->group(function () {
    // Beli Sampah
    Route::get('/buy-waste', [WasteController::class, 'index'])->name('buy-waste.index');

    // Cart
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Checkout (hanya 1 versi!)
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.form');
    Route::post('/checkout/prepare', [CheckoutController::class, 'prepare'])->name('checkout.prepare');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/remove', [CheckoutController::class, 'remove'])->name('checkout.remove');
    Route::post('/checkout/add', [CheckoutController::class, 'add'])->name('checkout.add');
    Route::get('/checkout/cart', [CheckoutController::class, 'cart'])->name('checkout.cart');
    Route::get('/checkout/qr/{id}', [CheckoutController::class, 'qrView'])->name('checkout.qr');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}/status', [TransactionController::class, 'status'])->name('transactions.status');

    // Jual Sampah & Edukasi
    // di dalam middleware auth:web group
 Route::get('/jual-barang', [SellController::class, 'index'])->name('jual-barang');

// alias agar pemanggilan lama `sell-waste.index` tetap jalan
Route::get('/sell-waste', fn () => redirect()->route('jual-barang'))->name('sell-waste.index');
    Route::post('/jual-barang', [SellController::class, 'store'])->name('sell-waste.store');
    Route::get('/sell-waste/types/{catId}', [SellController::class, 'getTypes']);
    Route::view('/edu', 'user.edukasi.index')->name('edu.index');
});

// ================== PRODUK ==================
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/detail-barang/{id}', [ProductController::class, 'detailBarang'])->name('detail-barang');

// ================== STAFF ==================
Route::prefix('staff')->name('staff.')->middleware('auth:staff')->group(function () {
    // Waste management
    Route::get('/wastes', [\App\Http\Controllers\Staff\WasteManagementController::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [\App\Http\Controllers\Staff\WasteManagementController::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [\App\Http\Controllers\Staff\WasteManagementController::class, 'storeCategory'])->name('wastes.category.store');
    Route::get('/wastes/type/create', [\App\Http\Controllers\Staff\WasteManagementController::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [\App\Http\Controllers\Staff\WasteManagementController::class, 'storeType'])->name('wastes.type.store');
    Route::post('/wastes/stock', [\App\Http\Controllers\Staff\WasteManagementController::class, 'addStock'])->name('wastes.stock.add');
    Route::get('/wastes/type/{id}/edit', [\App\Http\Controllers\Staff\WasteManagementController::class, 'editType'])->name('wastes.type.edit');
    Route::put('/wastes/type/{id}', [\App\Http\Controllers\Staff\WasteManagementController::class, 'updateType'])->name('wastes.type.update');

    // Sell requests
    Route::get('/sell-requests', [\App\Http\Controllers\Staff\SellRequestController::class, 'index'])->name('sell_requests.index');

    // ✅ Tambahkan kembali route untuk jenis sampah yang hilang
    Route::get('/sell-types', [\App\Http\Controllers\Staff\SellTypeController::class, 'index'])->name('sell-types.index');
    Route::get('/sell-types/create', [\App\Http\Controllers\Staff\SellTypeController::class, 'create'])->name('sell-types.create');
    Route::post('/sell-types', [\App\Http\Controllers\Staff\SellTypeController::class, 'store'])->name('sell-types.store');
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

// ================== DEBUG SESSION (LOCAL ONLY) ==================
Route::get('/debug-session', function () {
    if (!app()->isLocal()) abort(404);

    return response()->json([
        'is_web_logged_in'   => Auth::guard('web')->check(),
        'is_admin_logged_in' => Auth::guard('admin')->check(),
        'is_staff_logged_in' => Auth::guard('staff')->check(),
        'web_user'   => Auth::guard('web')->user(),
        'admin_user' => Auth::guard('admin')->user(),
        'staff_user' => Auth::guard('staff')->user(),
        'session_id' => Session::getId(),
        'all_session_data' => Session::all(),
        'cookie_session' => request()->cookie(config('session.cookie')),
    ]);
});

// ================== FE STATIC PAGES ==================
// JANGAN tumpang tindih dengan route controller
Route::get('/stokadmin', fn () => view('stokadmin'))->name('stokadmin');
Route::get('/stokform', fn () => view('stok-form'))->name('stokform');
Route::get('/berandadmin', fn () => view('berandadmin'))->name('berandadmin');
Route::get('/detailadmin', fn () => view('detail-admin'))->name('detailadmin');
Route::get('/detailpengguna', fn () => view('detail-pengguna'))->name('detailpengguna');
Route::get('/tambahadmin', fn () => view('form-tambah-admin'))->name('tambahadmin');
Route::get('/listorderan', fn () => view('list-orderan'))->name('listorderan');
Route::get('/stoksampah', fn () => view('stoksampah'))->name('stoksampah');
Route::get('/banyaksampah', fn () => view('banyak-sampah'))->name('banyaksampah');
Route::get('/beranda', fn () => view('beranda'))->name('beranda');

// ⚠️ HAPUS ini biar nggak override SellController
// Route::get('/jual-barang', fn () => view('jualbarang'))->name('jual-barang');

Route::get('/belibarang', fn () => view('belibarang'))->name('belibarang');
Route::get('/detail-produk/{id}', fn ($id) => view('detailbarang', ['id' => $id]))->name('detail-produk');
Route::get('/co-detail', fn () => view('co-detail'));
Route::get('/payment', fn () => view('payment'));
Route::get('/detail_payment', fn () => view('detail_payment'))->name('detail_payment');
Route::get('/notifikasi', fn () => view('notifikasi'))->name('notifikasi');

// ⚠️ HAPUS ini biar nggak bentrok sama ProfileController
// Route::get('/profil', fn () => view('profil'))->name('profil');

Route::get('/berandavoucher', fn () => view('berandavoucher'))->name('berandavoucher');
Route::get('/tukarvoucher', fn () => view('tukarvoucher'))->name('tukarvoucher');
Route::get('/profileadmin', fn () => view('profileadmin'))->name('profileadmin');
Route::get('/detailstokstaff', fn () => view('detailstokstaff'))->name('detailstokstaff');
