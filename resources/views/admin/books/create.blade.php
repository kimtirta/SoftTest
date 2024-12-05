@extends('layouts.app')

@section('title', 'Add New Book')

@section('content')
<form action="{{ route('books.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label for="title" class="block text-gray-700">Book Title</label>
        <input type="text" name="title" id="title" class="mt-1 block w-full" required>
    </div>

    <div class="mb-4">
        <label for="author" class="block text-gray-700">Author</label>
        <input type="text" name="author" id="author" class="mt-1 block w-full" required>
    </div>

    <div class="mb-4">
        <label for="genre" class="block text-gray-700">Genre</label>
        <input type="text" name="genre" id="genre" class="mt-1 block w-full">
    </div>

    <div class="mb-4">
        <label for="synopsis" class="block text-gray-700">Synopsis</label>
        <textarea name="synopsis" id="synopsis" class="mt-1 block w-full"></textarea>
    </div>

    <div class="mb-4">
        <label for="available_copies" class="block text-gray-700">Available Copies</label>
        <input type="number" name="available_copies" id="available_copies" class="mt-1 block w-full" value="0" required>
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary">Save Book</button>
    </div>
</form>
@endsection
