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
use App\Http\Controllers\Staff\WasteManagementController as StaffWasteCtrl;
use App\Http\Controllers\Staff\SellTypeController as StaffSellTypeCtrl;
use App\Http\Controllers\Staff\StaffTransactionController as StaffTransactionCtrl;
use App\Http\Controllers\Staff\SellRequestController;
use App\Http\Controllers\Staff\SellTypeController;
use App\Http\Controllers\UserPointController;
use App\Models\SellWaste;
use App\Http\Controllers\HistoryController;

// ================== LANDING PAGE ==================
Route::get('/', fn() => view('LandingPage'))->name('home');

// ================== LOGIN & REGISTER ==================
Route::middleware('guest:web,admin,staff')->group(function () {
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
Route::middleware('auth:web')->get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::middleware('auth:admin')->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware('auth:staff')->get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

// ================== USER PROFILE ==================
Route::middleware('auth:web')->group(function () {
});
// ================== USER (web guard) ==================
Route::middleware('auth:web')->group(function(){
});
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

// ================== USER (web guard) - BELI & JUAL ==================
Route::middleware('auth:web')->group(function () {

    // Beli Sampah
    // Contact Us (static)
    Route::view('/contact', 'user.profile.contact')->name('contact');

    // Beli sampah
    Route::get('/buy-waste', [WasteController::class, 'index'])->name('buy-waste.index');

    // Cart & Checkout (CartController lama tetap ada)
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // CheckoutController (ini yang kita pakai buat alur QR)
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.form');
    Route::post('/checkout/prepare', [CheckoutController::class, 'prepare'])->name('checkout.prepare');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/remove', [CheckoutController::class, 'remove'])->name('checkout.remove');
    Route::post('/checkout/add', [CheckoutController::class, 'add'])->name('checkout.add');
    Route::get('/checkout/cart', [CheckoutController::class, 'cart'])->name('checkout.cart');
    Route::get('/checkout/qr/{id}', [CheckoutController::class, 'qrView'])->name('checkout.qr');


    // Transactions (User)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}/status', [TransactionController::class, 'status'])->name('transactions.status');

    // Jual Sampah
    Route::get('/jual-barang', [SellController::class, 'index'])->name('jual-barang');
    Route::post('/jual-barang', [SellController::class, 'store'])->name('sell-waste.store');
    Route::get('/sell-waste', fn () => redirect()->route('jual-barang'))->name('sell-waste.index');
    Route::get('/sell-waste/types/{catId}', [SellController::class, 'getTypes'])->name('sell-waste.types');

    // Transactions (Pembelian)
    Route::get('/transactions', [TransactionController::class,'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class,'show'])->name('transactions.show');
    Route::get('/transactions/{id}/status', [TransactionController::class,'status'])->name('transactions.status');

    // SELL WASTE
    Route::get('/sell-waste', [SellController::class,'index'])->name('sell-waste.index');
    Route::post('/sell-waste', [SellController::class,'store'])->name('sell-waste.store');
    Route::get('/sell-waste/types/{catId}', [SellController::class,'getTypes'])->name('sell-waste.types');
    Route::get('/sell/create', [SellController::class,'create'])->name('sell.create');


    // ================== HISTORY ==================
    Route::get('/history/sell', [HistoryController::class, 'sell'])->name('history.sell');


   // History Buy diarahkan ke TransactionController@index
Route::get('/history/buy', [TransactionController::class, 'index'])->name('history.buy');

Route::get('/history/points', [HistoryController::class, 'points'])->name('history.points');

    // Edukasi
    Route::view('/edu', 'user.edukasi.index')->name('edu.index');


    // Reward (Tukar Point)// Tukarkan poin
Route::get('/my-points', [UserPointController::class, 'index'])->name('user.points.index');


});

// ================== PRODUK ==================
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/detail-barang/{id}', [ProductController::class, 'detailBarang'])->name('detail-barang');

// ================== STAFF (auth:staff) ==================
Route::prefix('staff')->name('staff.')->middleware('auth:staff')->group(function () {

    // Kelola produk & sampah
    Route::get('/wastes', [StaffWasteCtrl::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [StaffWasteCtrl::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [StaffWasteCtrl::class, 'storeCategory'])->name('wastes.category.store');
    Route::get('/wastes/type/create', [StaffWasteCtrl::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [StaffWasteCtrl::class, 'storeType'])->name('wastes.type.store');
    Route::get('/wastes/type/{id}/edit', [StaffWasteCtrl::class, 'editType'])->name('wastes.type.edit');
    Route::put('/wastes/type/{id}', [StaffWasteCtrl::class, 'updateType'])->name('wastes.type.update');
    Route::delete('/wastes/type/{id}', [StaffWasteCtrl::class, 'deleteType'])->name('wastes.type.delete');
    Route::post('/wastes/stock', [StaffWasteCtrl::class, 'addStock'])->name('wastes.stock.add');

    // Transactions (Staff)
    Route::get('/transactions', [StaffTransactionCtrl::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [StaffTransactionCtrl::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{id}/update', [StaffTransactionCtrl::class, 'updateStatus'])->name('transactions.update');

    // Sell Requests (Staff)
    Route::get('/sell-requests', [\App\Http\Controllers\Staff\SellRequestController::class, 'index'])->name('sell_requests.index');
    Route::get('/sell-requests/{id}', [\App\Http\Controllers\Staff\SellRequestController::class, 'show'])->name('sell_requests.show');
    Route::post('/sell-requests/{id}/update', [\App\Http\Controllers\Staff\SellRequestController::class, 'updateStatus'])->name('sell_requests.update');

    // Sell Waste Types
    Route::get('/sell-types', [StaffSellTypeCtrl::class, 'index'])->name('sell-types.index');
    Route::get('/sell-types/create', [StaffSellTypeCtrl::class, 'create'])->name('sell-types.create');
    Route::post('/sell-types', [StaffSellTypeCtrl::class, 'store'])->name('sell-types.store');
});

// ================== ADMIN (auth:admin) ==================
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {

    // Kelola sampah & produk
    Route::get('/wastes', [\App\Http\Controllers\Admin\WasteManagementController::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [\App\Http\Controllers\Admin\WasteManagementController::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [\App\Http\Controllers\Admin\WasteManagementController::class, 'storeCategory'])->name('wastes.category.store');
    Route::get('/wastes/type/create', [\App\Http\Controllers\Admin\WasteManagementController::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [\App\Http\Controllers\Admin\WasteManagementController::class, 'storeType'])->name('wastes.type.store');
    Route::post('/wastes/stock', [\App\Http\Controllers\Admin\WasteManagementController::class, 'addStock'])->name('wastes.stock.add');
    Route::get('/wastes/type/{id}/edit', [\App\Http\Controllers\Admin\WasteManagementController::class, 'editType'])->name('wastes.type.edit');
    Route::put('/wastes/type/{id}', [\App\Http\Controllers\Admin\WasteManagementController::class, 'updateType'])->name('wastes.type.update');

    // Manage Users
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
        'web_user'           => Auth::guard('web')->user(),
        'admin_user'         => Auth::guard('admin')->user(),
        'staff_user'         => Auth::guard('staff')->user(),
        'session_id'         => Session::getId(),
        'all_session_data'   => Session::all(),
        'cookie_session'     => request()->cookie(config('session.cookie')),
    ]);
});

// ================== FE STATIC PAGES ==================
Route::view('/stokadmin', 'stokadmin')->name('stokadmin');
Route::view('/stokform', 'stok-form')->name('stokform');
Route::view('/berandadmin', 'berandadmin')->name('berandadmin');
Route::view('/detailadmin', 'detail-admin')->name('detailadmin');
Route::view('/detailpengguna', 'detail-pengguna')->name('detailpengguna');
Route::view('/tambahadmin', 'form-tambah-admin')->name('tambahadmin');
Route::view('/listorderan', 'list-orderan')->name('listorderan');
Route::view('/stoksampah', 'stoksampah')->name('stoksampah');
Route::view('/banyaksampah', 'banyak-sampah')->name('banyaksampah');
Route::view('/beranda', 'beranda')->name('beranda');
Route::view('/belibarang', 'belibarang')->name('belibarang');
Route::view('/detail-produk/{id}', 'detailbarang')->name('detail-produk');
Route::view('/co-detail', 'co-detail');
Route::view('/payment', 'payment');
Route::view('/detail_payment', 'detail_payment')->name('detail_payment');
Route::view('/notifikasi', 'notifikasi')->name('notifikasi');
Route::view('/berandavoucher', 'berandavoucher')->name('berandavoucher');
Route::view('/tukarvoucher', 'tukarvoucher')->name('tukarvoucher');
Route::view('/profileadmin', 'profileadmin')->name('profileadmin');
Route::view('/detailstokstaff', 'detailstokstaff')->name('detailstokstaff');