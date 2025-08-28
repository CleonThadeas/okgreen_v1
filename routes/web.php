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
    return view('stoksampah');
})->name('stoksampah');

Route::get('/banyaksampah', function (){
    return view('banyak-sampah');
})->name('banyaksampah');

Route::get('/beranda', function () {
    return view('beranda'); 
})->name('beranda');

Route::get('/jual-barang', function () {
    return view('jualbarang');
})->name('jual-barang');

Route::get('/belibarang', function () {
    return view('belibarang');
})->name('belibarang');

Route::get('/detail-produk/{id}', function ($id) {
    return view('detailbarang', ['id' => $id]);
})->name('detail-barang');

Route::get('/co-detail', function () {
    return view('co-detail');
});

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/payment', function () {
    return view('payment');
});

Route::get('/detail_payment', function () {
    return view('detail_payment');
})->name('detail_payment');

Route::get('/notifikasi', function () {
    return view('notifikasi');
})->name('notifikasi');

Route::get('/profil', function () {
    return view('profil');
})->name('profil');

