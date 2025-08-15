<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);         // ngambil semua user
    Route::get('/users/{id}', [UserController::class, 'show']);     // ngamvil detail user
    Route::post('/users', [UserController::class, 'store']);        // nambahin user
    Route::put('/users/{id}', [UserController::class, 'update']);   // ngaupdate user
    Route::delete('/users/{id}', [UserController::class, 'destroy']);// hapus akun user
});