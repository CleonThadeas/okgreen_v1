<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('LandingPage');
});

Route::get('/login', function () {
    return 'Halaman login belum dibuat';
})->name('login');

Route::get('/register', function () {
    return view('RegisterPage');
})->name('register');
