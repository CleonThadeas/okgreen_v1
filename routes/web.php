<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return response()->json(['message' => 'OKGREEN API']);
});