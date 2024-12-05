@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<div class="max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Edit Book: {{ $book->title }}</h2>

    <form action="{{ route('books.update', $book->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" class="mt-1 block w-full" required>
        </div>

        <div class="mb-4">
            <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" class="mt-1 block w-full" required>
        </div>

        <div class="mb-4">
            <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
            <input type="text" name="genre" id="genre" value="{{ old('genre', $book->genre) }}" class="mt-1 block w-full">
        </div>

        <div class="mb-4">
            <label for="synopsis" class="block text-sm font-medium text-gray-700">Synopsis</label>
            <textarea name="synopsis" id="synopsis" rows="4" class="mt-1 block w-full">{{ old('synopsis', $book->synopsis) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="available_copies" class="block text-sm font-medium text-gray-700">Available Copies</label>
            <input type="number" name="available_copies" id="available_copies" value="{{ old('available_copies', $book->available_copies) }}" class="mt-1 block w-full" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Book</button>
        </div>
    </form>
</div>
@endsection
