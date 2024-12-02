<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Rute untuk Admin
Route::get('/', function () {
    return view('admin.login'); // Mengarah ke file home.blade.php
});


Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Rute untuk User
Route::get('/user/login', [UserController::class, 'loginForm'])->name('user.login');
Route::post('/user/login', [UserController::class, 'login'])->name('user.login.submit');
Route::get('/user/logout', [UserController::class, 'logout'])->name('user.logout');
Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

// Admin routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('books', BookController::class);
    Route::resource('users', UserController::class);
    Route::get('loans', [LoanController::class, 'index'])->name('admin.loans.index');
    Route::post('loans/{loan}/return', [LoanController::class, 'markAsReturned'])->name('admin.loans.return');
    Route::get('transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');
    Route::post('transactions/{transaction}/paid', [TransactionController::class, 'markAsPaid'])->name('admin.transactions.paid');
});

// User routes
Route::group(['prefix' => 'user'], function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/books', [BookController::class, 'index'])->name('user.books.index');
    Route::post('/books/{book}/borrow', [LoanController::class, 'borrowBook'])->name('user.books.borrow');
    Route::get('/loans', [LoanController::class, 'userLoans'])->name('user.loans.index');
    Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('user.loans.return');
});
