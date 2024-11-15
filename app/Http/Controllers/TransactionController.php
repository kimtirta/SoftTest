<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('loan.user', 'loan.book')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function markAsPaid(Transaction $transaction)
    {
        $transaction->update(['paid' => true]);
        return redirect()->route('transactions.index')->with('success', 'Transaction marked as paid.');
    }
}
