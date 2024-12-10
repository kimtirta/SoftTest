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
    
    return view('admin.loans.index', compact('loans'));
}

  // Show the form for creating a new loan
  public function create()
  {
      $users = User::all();
      $books = Book::where('available_copies', '>', 0)->get(); // Only books available for loan
      return view('admin.loans.create', compact('users', 'books'));
  }

  // Store a newly created loan
  public function store(Request $request)
  {
      $validated = $request->validate([
          'user_id' => 'required|exists:users,id',
          'book_id' => 'required|exists:books,id',
          'due_date' => 'required|date|after:today',
      ]);

      $loan = Loan::create($validated);

      // Update book availability
      $loan->book->decrement('available_copies');

      return redirect()->route('admin.loans.index')->with('success', 'Loan created successfully.');
  }

  // Show a specific loan
  public function show(Loan $loan)
  {
      return view('admin.loans.show', compact('loan'));
  }

  // Show the form for editing a loan
  public function edit(Loan $loan)
  {
      $users = User::all();
      $books = Book::all();
      return view('admin.loans.edit', compact('loan', 'users', 'books'));
  }

  // Update a loan
  public function update(Request $request, Loan $loan)
  {
      $validated = $request->validate([
          'user_id' => 'required|exists:users,id',
          'book_id' => 'required|exists:books,id',
          'due_date' => 'required|date|after:today',
          'returned_date' => 'nullable|date|after_or_equal:due_date',
      ]);

      $loan->update($validated);

      return redirect()->route('admin.loans.index')->with('success', 'Loan updated successfully.');
  }

  // Delete a loan
  public function destroy(Loan $loan)
  {
      $loan->book->increment('available_copies'); // Restore book availability
      $loan->delete();

      return redirect()->route('admin.loans.index')->with('success', 'Loan deleted successfully.');
  }
}
