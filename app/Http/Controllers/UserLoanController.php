<?php
namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;

class UserLoanController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated manually
        if (!auth()->check()) {
            return redirect('/login'); // Redirect to login if not authenticated
        }

        // Fetch active loans for the authenticated user
        $activeLoans = Loan::with('book', 'transaction')
            ->where('user_id', auth()->id())
            ->whereNull('returned_date') // Active loans
            ->get();

        // Fetch past loans (returned loans)
        $pastLoans = Loan::with('book', 'transaction')
            ->where('user_id', auth()->id())
            ->whereNotNull('returned_date') // Past loans
            ->get();

        return view('users.loans.index', compact('activeLoans', 'pastLoans'));
    }

    public function markAsReturned($id)
    {
        // Find the loan by ID
        $loan = Loan::findOrFail($id);

        // Check if it's already marked as returned
        if ($loan->returned_date) {
            return redirect()->route('users.loans.index')->with('error', 'Loan is already returned.');
        }

        // Update the returned_date to the current date
        $loan->returned_date = now();
        $loan->save();

        // Update the transaction to mark it as paid (if applicable)
        $transaction = Transaction::where('loan_id', $loan->id)->first();
        if ($transaction) {
            $transaction->paid = 1; // Mark as fully paid
            $transaction->save();
        }

        return redirect()->route('users.loans.index')->with('success', 'Loan marked as returned.');
    }
}
