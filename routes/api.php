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
use App\Http\Controllers\Api\TransactionController; 
use App\Http\Controllers\Api\PointController; 


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

    // Contact - USER
    Route::post('/contact', [ContactController::class, 'store']); 
    Route::get('/contact/{messageId}', [ContactController::class, 'show']); 
    Route::post('/contact/{messageId}/reply', [ContactController::class, 'reply']); 

    // Transactions
    Route::post('/transactions', [TransactionController::class, 'create']); // buat transaksi baru
    Route::post('/transactions/{id}/items', [TransactionController::class, 'addItem']); // tambah item
    Route::post('/transactions/{id}/pay', [TransactionController::class, 'pay']); // bayar
    Route::get('/transactions/{id}/status', [TransactionController::class, 'status']); // cek status
    Route::get('/transactions/my', [TransactionController::class, 'myTransactions']); // list transaksi user

    // Point rewerd
    Route::get('/points', [PointController::class, 'myPoints']);
    Route::post('/points/add', [PointController::class, 'addPoints']);
    Route::get('/rewards', [PointController::class, 'rewards']);
    Route::post('/rewards/{id}/redeem', [PointController::class, 'redeem']);
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

    
    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    
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

    // Waste Types
    Route::get('/waste-types', [WasteTypeController::class, 'index']);
    Route::get('/waste-types/{id}', [WasteTypeController::class, 'show']);
    Route::post('/waste-types', [WasteTypeController::class, 'store']);
    Route::put('/waste-types/{id}', [WasteTypeController::class, 'update']);
    Route::delete('/waste-types/{id}', [WasteTypeController::class, 'destroy']);

    // Waste Categories
    Route::get('/waste-categories', [WasteCategoryController::class, 'index']);
    Route::get('/waste-categories/{id}', [WasteCategoryController::class, 'show']);
    Route::post('/waste-categories', [WasteCategoryController::class, 'store']);
    Route::put('/waste-categories/{id}', [WasteCategoryController::class, 'update']);
    Route::delete('/waste-categories/{id}', [WasteCategoryController::class, 'destroy']);

    // Waste Stock
    Route::get('/waste-stock', [WasteStockController::class, 'index']);
    Route::get('/waste-stock/{id}', [WasteStockController::class, 'show']);
    Route::post('/waste-stock', [WasteStockController::class, 'store']);
    Route::put('/waste-stock/{id}', [WasteStockController::class, 'update']);
    Route::delete('/waste-stock/{id}', [WasteStockController::class, 'destroy']);
});
