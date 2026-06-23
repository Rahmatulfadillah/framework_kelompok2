<?php

use App\Http\Controllers\Api\BookSearchController;
use App\Http\Controllers\Api\EmailValidationController;
use Illuminate\Support\Facades\Route;

// Email validation
Route::post('/email/validate', [EmailValidationController::class, 'validate'])->name('api.email.validate');

// Book search
Route::get('/books/search', [BookSearchController::class, 'search'])->name('api.books.search');
Route::get('/books/available', [BookSearchController::class, 'available'])->name('api.books.available');
Route::get('/books/{id}', [BookSearchController::class, 'show'])->name('api.books.detail');
