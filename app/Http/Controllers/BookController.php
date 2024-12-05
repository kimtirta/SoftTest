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

        return view('admin.books.index', ['books' => $books]);
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
// Show the form for creating a new book
public function create()
{
    return view('admin.books.create');
}

// Store a newly created book
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'genre' => 'nullable|string|max:255',
        'synopsis' => 'nullable|string',
        'available_copies' => 'required|integer|min:0',
    ]);

    Book::create([
        'title' => $request->title,
        'author' => $request->author,
        'genre' => $request->genre,
        'synopsis' => $request->synopsis,
        'available_copies' => $request->available_copies,
    ]);

    return redirect()->route('books.index')->with('success', 'Book added successfully');
}

public function edit($id)
{
    $book = Book::findOrFail($id);
    return view('admin.books.edit', compact('book'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'genre' => 'nullable|string|max:255',
        'synopsis' => 'nullable|string',
        'available_copies' => 'required|integer|min:0',
    ]);

    $book = Book::findOrFail($id);
    $book->update([
        'title' => $request->title,
        'author' => $request->author,
        'genre' => $request->genre,
        'synopsis' => $request->synopsis,
        'available_copies' => $request->available_copies,
    ]);

    return redirect()->route('books.index')->with('success', 'Book updated successfully.');
}


// Remove the specified book
public function destroy($id)
{
    $book = Book::findOrFail($id);
    $book->delete();

    return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
}


}
