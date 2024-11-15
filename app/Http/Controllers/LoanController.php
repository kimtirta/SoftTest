<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index()
    {
        // Mengambil semua pinjaman untuk admin
        $loans = Loan::with('book', 'user')->get();
        return view('admin.loans.index', compact('loans'));
    }

    public function userLoans()
    {
        // Mengambil pinjaman milik pengguna yang sedang login
        $loans = Loan::with('book')->where('user_id', auth()->id())->get();
        return view('loans.index', compact('loans'));
    }

    public function borrowBook(Request $request)
    {
        $request->validate(['book_id' => 'required|exists:books,id']);

        $activeLoans = Loan::where('user_id', auth()->id())->whereNull('returned_date')->count();

        if ($activeLoans >= 2) {
            return back()->withErrors(['limit' => 'You can only borrow 2 books at a time.']);
        }

        $book = Book::findOrFail($request->book_id);

        if ($book->available_copies < 1) {
            return back()->withErrors(['unavailable' => 'Book is currently unavailable.']);
        }

        $book->decrement('available_copies');

        Loan::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'due_date' => Carbon::now()->addWeek(),
        ]);

        return redirect()->route('user.loans.index')->with('success', 'Book borrowed successfully.');
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->user_id != auth()->id()) {
            abort(403);
        }

        $loan->update(['returned_date' => Carbon::now()]);
        $loan->book->increment('available_copies');

        if (Carbon::now()->greaterThan($loan->due_date)) {
            $daysLate = Carbon::now()->diffInDays($loan->due_date);
            Transaction::create([
                'loan_id' => $loan->id,
                'fine_amount' => $daysLate * 1000, // denda Rp1000/hari
            ]);
        }

        return redirect()->route('user.loans.index')->with('success', 'Book returned successfully.');
    }

    public function markAsReturned($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['returned_date' => Carbon::now()]);
        $loan->book->increment('available_copies');

        return redirect()->route('admin.loans.index')->with('success', 'Loan marked as returned.');
    }
}
