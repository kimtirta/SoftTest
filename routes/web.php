<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// Rute untuk Admin
Route::get('/', function () {
    return view('admin.login'); // Mengarah ke file home.blade.php
});

// Admin routes
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Book routes
Route::get('/admin/books', [BookController::class, 'index'])->name('books.index');
Route::post('/admin/books/{id}/borrow', [BookController::class, 'borrow'])->name('user.books.borrow');

// Loan routes
Route::get('/admin/loans', [LoanController::class, 'index'])->name('admin.loans.index');
Route::post('/admin/loans/{id}/return', [LoanController::class, 'markAsReturned'])->name('loans.return');

// Transaction routes
Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/admin/transactions/{id}/pay', [TransactionController::class, 'markAsPaid'])->name('transactions.markAsPaid');

// User routes
Route::get('/user/login', [UserController::class, 'login'])->name('user.login');
Route::post('/user/login', [UserController::class, 'loginSubmit'])->name('user.login.submit');
Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
Route::get('/user/books', [BookController::class, 'indexForUsers'])->name('user.books.index');
