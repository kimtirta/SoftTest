<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminLoanController extends Controller
{
    public function index()
    {
        // Fetch all active loans (not returned yet)
        $activeLoans = Loan::with(['user', 'book', 'transaction'])
            ->whereNull('returned_date')
            ->get();
    
        // Fetch all past loans (returned loans)
        $pastLoans = Loan::with(['user', 'book', 'transaction'])
            ->whereNotNull('returned_date')
            ->get();
    
        // Fetch all loans with pagination
        $loans = Loan::with(['user', 'book'])->paginate(10);
    
        // Return the view with both active and past loans
        return view('admin.loans.index', compact('activeLoans', 'pastLoans', 'loans'));
    }
    
    

    public function returnRequests()
    {
        // Get all loans that have a returned_date but are not marked as fully paid
        $returnRequests = Loan::with('transaction')->whereNotNull('returned_date')->get();

        return view('admin.loans.return_requests', compact('returnRequests'));
    }

    public function approveReturn($id)
    {
        // Find the loan by ID
        $loan = Loan::findOrFail($id);

        // Approve the return request by marking it as paid and updating returned date
        $loan->transaction->update(['paid' => 1]);  // Mark transaction as paid
        $loan->returned_date = now();  // Update returned date
        $loan->save();

        return redirect()->back()->with('success', 'Return request approved.');
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

      return redirect()->route('admin.loans.index')->with('success', 'Loan created successfully.');
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
      ]);

      $loan = Loan::findOrFail($id);
      $loan->update([
          'user_id' => $request->user_id,
          'book_id' => $request->book_id,
          'due_date' => $request->due_date,
      ]);

      return redirect()->route('admin.loans.index')->with('success', 'Loan updated successfully.');
  }

  // Delete a loan
  public function destroy($id)
  {
      $loan = Loan::findOrFail($id);
      $loan->delete();

      return redirect()->route('admin.loans.index')->with('success', 'Loan deleted successfully.');
  }


}
