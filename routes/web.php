<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

// Guest routes (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Auth routes (sudah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Books CRUD
    Route::resource('books', BookController::class);

    // Loans Management
    Route::resource('loans', LoanController::class);
    Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
});

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});
