<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Book;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'book'])->get();

        return view('loans.index', ['loans' => $loans]);
    }

    public function markAsReturned(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->returned_date) {
            return back()->withErrors(['This loan is already marked as returned']);
        }

        $loan->update(['returned_date' => now()]);

        $loan->book->increment('available_copies');

        return back()->with('success', 'Book marked as returned successfully!');
    }
}
