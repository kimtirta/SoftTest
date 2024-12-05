@extends('layouts.app')

@section('title', 'Manage Books')

@section('content')
<div class="mb-4">
    <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    @foreach($books as $book)
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h3 class="text-xl font-semibold">{{ $book->title }}</h3>
        <p class="text-gray-500"><strong>Author:</strong> {{ $book->author }}</p>
        <p class="text-gray-500"><strong>Genre:</strong> {{ $book->genre }}</p>
        <p class="text-gray-500"><strong>Synopsis:</strong> {{ Str::limit($book->synopsis, 100) }}</p>
        <p class="text-gray-500"><strong>Available Copies:</strong> {{ $book->available_copies }}</p>

        <div class="mt-4">
            <a href="{{ route('books.edit', $book->id) }}" class="text-blue-500">Edit</a>
            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this book?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

{{ $books->links() }}  <!-- Pagination links -->
@endsection
