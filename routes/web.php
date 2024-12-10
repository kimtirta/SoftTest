<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserLoanController;
use App\Http\Controllers\AdminLoanController;
use App\Models\Admin;
// Admin routes


// Rute untuk Admin
Route::get('/', function () {
    return view('admin.login'); // Mengarah ke file home.blade.php
});
// Admin routes
Route::resource('books', BookController::class);
Route::resource('users', UserController::class);

Route::prefix('admin/loans')->group(function () {
    Route::get('/not-returned', [AdminLoanController::class, 'notReturned'])->name('admin.loans.notReturned');
    Route::get('/returned', [AdminLoanController::class, 'index'])->name('admin.loans.returned');
    Route::get('/overdue', [AdminLoanController::class, 'overdue'])->name('admin.loans.overdue');
});
Route::resource('admin/loans', AdminLoanController::class);
Route::get('admin/loans/returned', [AdminLoanController::class, 'returned'])->name('admin.loans.returned');

Route::get('loans', [UserLoanController::class, 'index'])->name('users.loans.index');
Route::get('admin/loans', [AdminLoanController::class, 'index'])->name('admin.loans.index');
Route::get('admin/loans/create', [AdminLoanController::class, 'create'])->name('admin.loans.create');
Route::get('admin/loans/{loan}/edit', [AdminLoanController::class, 'edit'])->name('admin.loans.edit');
Route::post('admin/loans/{loan}/return', [AdminLoanController::class, 'markAsReturned'])->name('admin.loans.return');

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Book routes
Route::get('/admin/books', [BookController::class, 'index'])->name('books.index');
Route::post('/admin/books/{id}/borrow', [BookController::class, 'borrow'])->name('user.books.borrow');
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::resource('books', BookController::class);  // This handles CRUD operations
});
// Loan routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('loans', AdminLoanController::class);
});
// Transaction routes
Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/admin/transactions/{id}/pay', [TransactionController::class, 'markAsPaid'])->name('transactions.markAsPaid');

// User routes
Route::get('/user/login', [UserController::class, 'login'])->name('user.login');
Route::post('/user/login', [UserController::class, 'loginSubmit'])->name('user.login.submit');
Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
Route::get('/user/books', [BookController::class, 'indexForUsers'])->name('user.books.index');

