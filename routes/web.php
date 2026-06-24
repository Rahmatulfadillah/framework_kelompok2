<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanHistoryController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Guest routes (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Link verifikasi telah dikirim ulang ke email Anda.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Logout route - accessible untuk user yang sudah login (belum terverifikasi juga bisa logout)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Auth routes (sudah login)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('admin')->group(function () {
        // Books Management (except index & show)
        Route::resource('books', BookController::class)->except(['index', 'show']);

        // Loans Management
        Route::resource('loans', LoanController::class);
        Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
    });

    // Books Read-Only for all users
    Route::get('books', [BookController::class, 'index'])->name('books.index');
    Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');

    // Loan History for all users
    Route::get('/my-loans', [LoanHistoryController::class, 'index'])->name('loans.history');
    Route::post('/books/{book}/borrow', [LoanController::class, 'userBorrow'])->name('books.borrow');
});

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});
