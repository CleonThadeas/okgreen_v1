<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| Guest Routes (tidak login)
|--------------------------------------------------------------------------
| Hanya bisa diakses jika belum login.
| Register hanya untuk user, admin/staff dibuat manual atau via dashboard admin.
*/
Route::middleware('guest')->group(function () {
    // Register User
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login (bisa untuk semua guard via CustomLoginController atau default)
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Forgot Password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (login dengan salah satu guard)
|--------------------------------------------------------------------------
| Menggunakan middleware auth:any supaya bisa cek admin/staff/user
| Jika mau default guard, ubah jadi auth:web.
*/
Route::middleware(['auth:admin,staff,web'])->group(function () {
    // Email verification notice page
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Email verification link handling
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Resend email verification
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Confirm password before sensitive actions
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Update password
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Role-specific routes (opsional)
|--------------------------------------------------------------------------
| Jika mau pisahkan dashboard atau route berdasarkan role.
*/
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', fn () => 'Selamat datang Admin!');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/user/dashboard', fn () => 'Selamat datang User!');
});

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/staff/dashboard', fn () => 'Selamat datang Staff!');
});
