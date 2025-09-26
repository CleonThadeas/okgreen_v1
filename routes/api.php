<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\WasteTypeController;
use App\Http\Controllers\Api\WasteStockController;
use App\Http\Controllers\Api\WasteCategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserTokenController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\PointController; 
use App\Http\Controllers\Api\SellController;
use App\Http\Controllers\Api\SellWasteTypeController;
use App\Http\Controllers\Api\Staff\StaffTransactionController;
use App\Http\Controllers\Api\Staff\StaffSellController;
use App\Http\Controllers\Api\Staff\SellRequestController;
use App\Http\Controllers\Api\Staff\WasteManagementController;


// ===========================
// USER AUTH (Sanctum)
// ===========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [ProfileController::class, 'update']); 

    // Waste Types (read-only)
    Route::get('/waste-types', [WasteTypeController::class, 'index']);
    Route::get('/waste-types/{id}', [WasteTypeController::class, 'show']);

    // Waste Categories (read-only)
    Route::get('/waste-categories', [WasteCategoryController::class, 'index']);
    Route::get('/waste-categories/{id}', [WasteCategoryController::class, 'show']);

    // Waste Stock (read-only)
    Route::get('/waste-stock', [WasteStockController::class, 'index']);
    Route::get('/waste-stock/{id}', [WasteStockController::class, 'show']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    Route::post('/update-fcm-token', [UserTokenController::class, 'updateFcmToken']);

    // Contact 
    Route::post('/contact', [ContactController::class, 'store']); 
    Route::get('/contact/{messageId}', [ContactController::class, 'show']); 
    Route::post('/contact/{messageId}/reply', [ContactController::class, 'reply']); 

    // Cart dan checkout sampah
    Route::post('/cart/add', [CartController::class, 'add']); 
    Route::post('/cart/checkout', [CartController::class, 'checkout']); 
    Route::get('/cart/transactions', [CartController::class, 'myTransactions']); 
    Route::get('/cart/transactions/{id}', [CartController::class, 'show']); 

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'myTransactions']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/transactions/{id}/status', [TransactionController::class, 'status']);

    // Point reward
    Route::get('/points', [PointController::class, 'myPoints']);
    Route::post('/points/add', [PointController::class, 'addPoints']);
    Route::get('/rewards', [PointController::class, 'rewards']);
    Route::post('/rewards/{id}/redeem', [PointController::class, 'redeem']);

    // SellWaste (user jual sampah)
    Route::get('/sells', [SellController::class, 'index']);               // List transaksi jual user
    Route::post('/sells', [SellController::class, 'store']);              // Buat transaksi jual
    Route::get('/sells/{id}', [SellController::class, 'show']);          // Detail transaksi jual
    Route::get('/sells/category/{catId}/types', [SellController::class, 'getTypes']); // Ambil jenis sampah berdasarkan kategori

    // SellWasteType - user hanya bisa lihat
    Route::get('/sell-types', [SellWasteTypeController::class, 'index']);   // List jenis sampah jual
    Route::get('/sell-types/{id}', [SellWasteTypeController::class, 'show']); // Detail jenis

});


// ============================
// STAFF AUTH
// ============================
Route::post('/staff/login', [StaffController::class, 'login']);

Route::middleware(['auth:staff'])->prefix('staff')->group(function () {

    Route::post('/logout', [StaffController::class, 'logout']);
    Route::get('/me', [StaffController::class, 'me']);

    // Waste Stock
    Route::get('/waste-stock', [WasteStockController::class, 'index']);
    Route::get('/waste-stock/{id}', [WasteStockController::class, 'show']);
    Route::post('/waste-stock', [WasteStockController::class, 'store']);
    Route::put('/waste-stock/{id}', [WasteStockController::class, 'update']);

    // Contact 
    Route::get('/contact', [ContactController::class, 'index']); 
    Route::get('/contact/{messageId}', [ContactController::class, 'show']); 
    Route::post('/contact/{messageId}/reply', [ContactController::class, 'reply']); 

    // Transaksi Beli (Staff) 
    Route::get('/transactions', [StaffTransactionController::class, 'index']);         // List transaksi beli
    Route::get('/transactions/{id}', [StaffTransactionController::class, 'show']);     // Detail transaksi beli
    Route::put('/transactions/{id}/status', [StaffTransactionController::class, 'updateStatus']); // Update status beli
    Route::get('/transactions/by-waste/{wasteTypeId}', [StaffTransactionController::class, 'byWaste']); // Filter by waste type

    // Transaksi Jual (Staff) 
    Route::get('/sells', [StaffSellController::class, 'index']);                        // List transaksi jual
    Route::get('/sells/{id}', [StaffSellController::class, 'show']);                    // Detail transaksi jual
    Route::put('/sells/{id}/status', [StaffSellController::class, 'updateStatus']);     // Update status jual

    // Sell Requests 
    Route::get('/sell-requests', [SellRequestController::class, 'index']);             // List permintaan jual
    Route::get('/sell-requests/{id}', [SellRequestController::class, 'show']);         // Detail permintaan jual
    Route::put('/sell-requests/{id}/status', [SellRequestController::class, 'updateStatus']); // Approve / cancel request

    // SellWasteType - CRUD untuk staff 
    Route::get('/sell-types', [SellWasteTypeController::class, 'index']);       // List semua jenis
    Route::post('/sell-types', [SellWasteTypeController::class, 'store']);      // Tambah jenis
    Route::get('/sell-types/{id}', [SellWasteTypeController::class, 'show']);   // Detail jenis
    Route::put('/sell-types/{id}', [SellWasteTypeController::class, 'update']); // Update jenis
    Route::delete('/sell-types/{id}', [SellWasteTypeController::class, 'destroy']); // Hapus jenis

    // Waste Types & Categories 
    Route::get('/wastes', [WasteManagementController::class, 'index']); 
    Route::post('/wastes/categories', [WasteManagementController::class, 'storeCategory']); 
    Route::post('/wastes', [WasteManagementController::class, 'storeType']); 
    Route::put('/wastes/{id}', [WasteManagementController::class, 'updateType']); 
    Route::delete('/wastes/{id}', [WasteManagementController::class, 'deleteType']); 
    Route::post('/wastes/stock', [WasteManagementController::class, 'addStock']); 
    Route::get('/wastes/{id}/transactions', [WasteManagementController::class, 'transactions']); 

});


// ============================
// ADMIN AUTH
// ============================
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware(['auth:admin', 'is_admin'])->prefix('admin')->group(function () {

    Route::post('/logout', [AdminAuthController::class, 'logout']);

    // Manage User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Manage Staff
    Route::get('/staff', [StaffController::class, 'index']);
    Route::get('/staff/{id}', [StaffController::class, 'show']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::put('/staff/{id}', [StaffController::class, 'update']);
    Route::delete('/staff/{id}', [StaffController::class, 'destroy']);

    // Manage Waste Types
    Route::get('/waste-types', [WasteTypeController::class, 'index']);
    Route::get('/waste-types/{id}', [WasteTypeController::class, 'show']);
    Route::post('/waste-types', [WasteTypeController::class, 'store']);
    Route::put('/waste-types/{id}', [WasteTypeController::class, 'update']);
    Route::delete('/waste-types/{id}', [WasteTypeController::class, 'destroy']);

    // Manage Waste Categories
    Route::get('/waste-categories', [WasteCategoryController::class, 'index']);
    Route::get('/waste-categories/{id}', [WasteCategoryController::class, 'show']);
    Route::post('/waste-categories', [WasteCategoryController::class, 'store']);
    Route::put('/waste-categories/{id}', [WasteCategoryController::class, 'update']);
    Route::delete('/waste-categories/{id}', [WasteCategoryController::class, 'destroy']);

    // Manage Waste Stock
    Route::get('/waste-stock', [WasteStockController::class, 'index']);
    Route::get('/waste-stock/{id}', [WasteStockController::class, 'show']);
    Route::post('/waste-stock', [WasteStockController::class, 'store']);
    Route::put('/waste-stock/{id}', [WasteStockController::class, 'update']);
    Route::delete('/waste-stock/{id}', [WasteStockController::class, 'destroy']);

    // Manage Reward Rules
    Route::get('/point-rewards', [PointController::class, 'index']);
    Route::post('/point-rewards', [PointController::class, 'store']);
    Route::put('/point-rewards/{id}', [PointController::class, 'update']);
    Route::delete('/point-rewards/{id}', [PointController::class, 'destroy']);
});
