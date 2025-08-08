<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Auth\CustomLoginController;

Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomLoginController::class, 'login']);
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout'); 

Route::get('/', function () {
    return view('welcome');
});
// Dashboard User
Route::middleware(['auth'])->get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

// Dashboard Admin
Route::middleware(['auth'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

// Dashboard Staff
Route::middleware(['auth'])->get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
