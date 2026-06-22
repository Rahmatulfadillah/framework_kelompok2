<?php

use Illuminate\Support\Facades\Route;

// TAMBAHKAN INI - Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/login', function () {
    return view('auth.login');
});

Route::get('/auth/register', function () {
    return view('auth.register');
});