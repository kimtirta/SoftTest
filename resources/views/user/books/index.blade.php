@extends('layouts.app')

@section('title', 'Borrow Books')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-4">Borrow Books</h1>
    @if($books && $books->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($books as $book)
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold">{{ $book->title }}</h2>
                    <p class="text-gray-600">{{ $book->author }}</p>
                    <form action="{{ route('user.books.borrow', $book->id) }}" method="POST" class="mt-2">
                        @csrf
                        @if($book->is_available)
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Borrow</button>
                        @else
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" disabled>Not Available</button>
                        @endif
                    </form>
                </div>
            @endforeach
        </div>
        {{ $books->links() }}
    @else
        <p>No books available at the moment.</p>
    @endif
</div>
@endsection
