<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminLoanController extends Controller
{
    
    public function overdue()
    {
        $loans = Loan::whereNull('returned_date')
            ->where('due_date', '<', Carbon::now())
            ->with('user', 'book')
            ->paginate(10);
    
        return view('admin.loans.loans_not_returned', compact('loans'))
            ->with('filter', 'overdue');
    }

    public function index()
    {
        // Fetch all active loans (not returned yet)
        $loans = Loan::with(['user', 'book'])
            ->whereNull('returned_date')
            ->paginate(10);
        
        // Fetch all past loans (returned loans)
        $pastLoans = Loan::with(['user', 'book'])
            ->whereNotNull('returned_date')
            ->paginate(10);
    
        // Return the view with both active and past loans
        return view('admin.loans.index', compact('loans', 'pastLoans'));
    }
    
// app/Http/Controllers/LoanController.php

public function returned()
{
    // Fetch past loans (returned)
    $pastLoans = Loan::with(['user', 'book'])
        ->whereNotNull('returned_date')
        ->paginate(10); // Paginate the returned loans
    
    // Return the view with pastLoans data
    return view('admin.loans.returned', compact('pastLoans'));
}

    
    
    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        return view('admin.loans.show', compact('loan'));
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

  public function update(Request $request, $id)
{
    $loan = Loan::findOrFail($id);

    // Update loan details
    $loan->user_id = $request->user_id;
    $loan->book_id = $request->book_id;
    $loan->due_date = $request->due_date;

    // Handle returned_date logic
    if ($request->returned_status === 'returned') {
        $loan->returned_date = $request->returned_date;
    } else {
        $loan->returned_date = null; // Set to null if not returned
    }

    $loan->save();

    return redirect()->route('admin.loans.index')->with('success', 'Loan updated successfully');
}

  // Delete a loan
  public function destroy($id)
  {
      $loan = Loan::findOrFail($id);
      $loan->delete();

      return redirect()->route('admin.loans.index')->with('success', 'Loan deleted successfully.');
  }
  public function markAsReturned($loanId)
  {
      // Find the loan by ID
      $loan = Loan::findOrFail($loanId);

      // Mark the loan as returned by updating the `returned_date`
      $loan->returned_date = now();
      $loan->save();

      // Redirect back to the loans list with a success message
      return redirect()->route('admin.loans.index')->with('success', 'Loan marked as returned.');
  }

}
