<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Loan;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['loan.user', 'loan.book'])->get();

        return view('transactions.index', ['transactions' => $transactions]);
    }

    public function markAsPaid(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->paid) {
            return back()->withErrors(['This fine is already paid']);
        }

        $transaction->update(['paid' => true]);

        return back()->with('success', 'Transaction marked as paid successfully!');
    }
}
