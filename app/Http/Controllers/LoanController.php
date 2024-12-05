<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;


class LoanController extends Controller
{
// Show the list of loans
public function index()
{
    $loans = Loan::with(['user', 'book'])->paginate(10);
    return view('admin.loans.index', compact('loans'));
}

// Show the form to create a new loan
public function create()
{
    $users = User::all();
    $books = Book::all();
    return view('admin.loans.create', compact('users', 'books'));
}

// Store a new loan
public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'book_id' => 'required|exists:books,id',
        'due_date' => 'required|date',
    ]);

    Loan::create([
        'user_id' => $request->user_id,
        'book_id' => $request->book_id,
        'due_date' => $request->due_date,
    ]);

    return redirect()->route('admin.loans.index')->with('success', 'Loan created successfully');
}

// Show the form to edit a loan
public function edit($id)
{
    $loan = Loan::findOrFail($id);
    $users = User::all();
    $books = Book::all();
    return view('admin.loans.edit', compact('loan', 'users', 'books'));
}

// Update an existing loan
public function update(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'book_id' => 'required|exists:books,id',
        'due_date' => 'required|date',
        'returned_date' => 'nullable|date',
    ]);

    $loan = Loan::findOrFail($id);
    $loan->update([
        'user_id' => $request->user_id,
        'book_id' => $request->book_id,
        'due_date' => $request->due_date,
        'returned_date' => $request->returned_date,
    ]);

    return redirect()->route('admin.loans.index')->with('success', 'Loan updated successfully');
}

// Delete a loan
public function destroy($id)
{
    Loan::findOrFail($id)->delete();
    return redirect()->route('admin.loans.index')->with('success', 'Loan deleted successfully');
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
