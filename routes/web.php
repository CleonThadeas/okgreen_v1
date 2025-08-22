<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('LandingPage');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('RegisterPage');
})->name('register');

Route::get('/stokadmin', function () {
    return view('stokadmin');
})->name('stokadmin');

Route::get('/stokform', function () {
    return view('stok-form');
})->name('stokform');

Route::get('/berandadmin', function () {
    return view('berandadmin');
})->name('berandadmin');

Route::get('/detailadmin', function () {
    return view('detail-admin');
})->name('detailadmin');

Route::get('/detailpengguna', function () {
    return view('detail-pengguna');
})->name('detailpengguna');

Route::get('/tambahadmin' , function () {
    return view('form-tambah-admin');
})->name('tambahadmin');

Route::get('/listorderan', function () {
    return view('list-orderan');
})->name('listorderan');

Route::get('/stoksampah', function (){
    return view('stok-sampah');
})->name('stoksampah');

Route::get('/banyaksampah', function (){
    return view('banyak-sampah');
})->name('banyaksampah');