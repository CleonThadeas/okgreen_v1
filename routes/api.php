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
use App\Http\Controllers\Api\AdminWasteCategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserTokenController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CartController;
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

    // Manage Waste Types
    Route::get('/waste-types', [WasteTypeController::class, 'index']);
    Route::get('/waste-types/{id}', [WasteTypeController::class, 'show']);
    Route::post('/waste-types', [WasteTypeController::class, 'store']);
    Route::put('/waste-types/{id}', [WasteTypeController::class, 'update']);
    Route::delete('/waste-types/{id}', [WasteTypeController::class, 'destroy']);

    // Manage Waste Categories
    Route::get('/waste-categories', [AdminWasteCategoryController::class, 'index']);
    Route::get('/waste-categories/{id}', [AdminWasteCategoryController::class, 'show']);
    Route::post('/waste-categories', [AdminWasteCategoryController::class, 'store']);
    Route::put('/waste-categories/{id}', [AdminWasteCategoryController::class, 'update']);
    Route::delete('/waste-categories/{id}', [AdminWasteCategoryController::class, 'destroy']);

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
