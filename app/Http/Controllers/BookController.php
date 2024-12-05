<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(10);

        return view('books.index', ['books' => $books]);
    }

    public function indexForUsers()
    {
        $books = Book::paginate(10);

        return view('books.index', ['books' => $books]);
    }

    public function borrow(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if ($book->available_copies <= 0) {
            return back()->withErrors(['This book is not available']);
        }

        Loan::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'due_date' => now()->addDays(14),
        ]);

        $book->decrement('available_copies');

        return back()->with('success', 'Book borrowed successfully!');
    }
}
