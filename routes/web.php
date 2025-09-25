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
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Staff\WasteManagementController as StaffWasteCtrl;
use App\Http\Controllers\Admin\WasteManagementController as AdminWasteCtrl;
use App\Http\Controllers\Staff\StaffTransactionController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\Staff\SellRequestController;
use App\Http\Controllers\Staff\SellTypeController;
use App\Http\Controllers\UserPointController;
use App\Models\SellWaste;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Staff\ContactController as StaffContactController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Staff\NotificationController as StaffNotificationController;

// ================== HOME ==================
Route::get('/', function () {
    return view('welcome');
});

// ================== LOGIN & LOGOUT ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [MultiGuardLoginController::class, 'create'])->name('login');
    Route::post('/login', [MultiGuardLoginController::class, 'store']);
});

Route::post('/logout', [MultiGuardLoginController::class, 'destroy'])
    ->middleware('auth:admin,staff,web')
    ->name('logout');

// ================== DASHBOARD ==================
Route::middleware(['auth:web'])->get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth:staff'])->get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

Route::middleware('auth:web')->get('/dashboard/buy', function () {
    return redirect()->route('buy-waste.index');
})->name('dashboard.buy');


// ================== USER (web guard) ==================
Route::middleware('auth:web')->group(function(){

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Contact Us (static)
    Route::view('/contact', 'user.profile.contact')->name('contact');

    // Beli sampah
    Route::get('/buy-waste', [WasteController::class, 'index'])->name('buy-waste.index');

    // Checkout
    Route::get('/checkout/form', [CheckoutController::class, 'show'])->name('checkout.form');
    Route::post('/checkout/prepare', [CheckoutController::class, 'prepare'])->name('checkout.prepare');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::get('/checkout/qr/{id}', [CheckoutController::class, 'qrView'])->name('checkout.qr');

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

//Notifikasi&Contact
Route::get('/contact', [ContactController::class,'create'])->name('contact');
    Route::post('/contact', [ContactController::class,'store'])->name('contact.store');


       Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');


    
});

// ================== STAFF ==================
Route::prefix('staff')->name('staff.')->middleware('auth:staff')->group(function(){
    // Kelola produk
    Route::get('/wastes', [StaffWasteCtrl::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [StaffWasteCtrl::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [StaffWasteCtrl::class, 'storeCategory'])->name('wastes.category.store');
    Route::get('/wastes/type/create', [StaffWasteCtrl::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [StaffWasteCtrl::class, 'storeType'])->name('wastes.type.store');
    Route::get('/wastes/type/{id}/edit', [StaffWasteCtrl::class, 'editType'])->name('wastes.type.edit');
    Route::put('/wastes/type/{id}', [StaffWasteCtrl::class, 'updateType'])->name('wastes.type.update');
    Route::delete('/wastes/type/{id}', [StaffWasteCtrl::class, 'deleteType'])->name('wastes.type.delete');
    Route::post('/wastes/stock', [StaffWasteCtrl::class, 'addStock'])->name('wastes.stock.add');

    // Transaksi
    Route::get('/transactions', [StaffTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [StaffTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{id}/update', [StaffTransactionController::class, 'updateStatus'])->name('transactions.update');

    // Sell requests
    Route::get('/sell-requests', [SellRequestController::class, 'index'])->name('sell_requests.index');
    Route::get('/sell-requests/{id}', [SellRequestController::class, 'show'])->name('sell_requests.show');
    Route::post('/sell-requests/{id}/update', [SellRequestController::class, 'updateStatus'])
    ->name('sell_requests.update');


    // Sell waste types (jenis sampah)
    Route::get('/sell-types', [SellTypeController::class,'index'])->name('sell-types.index');
    Route::get('/sell-types/create', [SellTypeController::class,'create'])->name('sell-types.create');
    Route::post('/sell-types', [SellTypeController::class,'store'])->name('sell-types.store');

    //Notifikasi&Contact
    Route::get('/contacts', [StaffContactController::class,'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [StaffContactController::class,'show'])->name('contacts.show');
    Route::post('/contacts/{id}/reply', [StaffContactController::class,'reply'])->name('contacts.reply');

    Route::get('/notifications', [StaffNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [StaffNotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{id}/read', [StaffNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [StaffNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// ================== ADMIN ==================
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function(){
    Route::get('/wastes', [AdminWasteCtrl::class, 'index'])->name('wastes.index');
    Route::get('/wastes/category/create', [AdminWasteCtrl::class, 'createCategory'])->name('wastes.category.create');
    Route::post('/wastes/category', [AdminWasteCtrl::class, 'storeCategory'])->name('wastes.category.store');
    Route::get('/wastes/type/create', [AdminWasteCtrl::class, 'createType'])->name('wastes.type.create');
    Route::post('/wastes/type', [AdminWasteCtrl::class, 'storeType'])->name('wastes.type.store');
    Route::get('/wastes/type/{id}/edit', [AdminWasteCtrl::class, 'editType'])->name('wastes.type.edit');
    Route::put('/wastes/type/{id}', [AdminWasteCtrl::class, 'updateType'])->name('wastes.type.update');
    Route::post('/wastes/stock', [AdminWasteCtrl::class, 'addStock'])->name('wastes.stock.add');

    // Manage users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});

// ================== DEBUG SESSION ==================
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
