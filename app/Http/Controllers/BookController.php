<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
{
    // Fetch books with optional search functionality
    $books = Book::query();

    if ($request->has('search')) {
        $books->where('title', 'like', '%' . $request->search . '%')
            ->orWhere('author', 'like', '%' . $request->search . '%')
            ->orWhere('genre', 'like', '%' . $request->search . '%');
    }

    $books = $books->paginate(10);

    // Pass $books to the view
    return view('books.index', compact('books'));
}


    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'available_copies' => 'required|integer|min:1',
        ]);

        Book::create($request->all());
        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'available_copies' => 'required|integer|min:1',
        ]);

        $book->update($request->all());
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    public function borrow($id)
{
    $book = Book::findOrFail($id);

    // Check if the user already borrowed max books
    $user = auth()->user();
    if ($user->loans->count() >= 2) {
        return back()->with('error', 'You have reached the maximum number of borrowed books.');
    }

    // Create new loan
    Loan::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'due_date' => now()->addWeek(),
    ]);

    return back()->with('success', 'Book borrowed successfully!');
}

}



