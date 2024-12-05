@extends('layouts.app')

@section('title', 'Edit Loan')

@section('content')
<form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label for="user_id" class="block text-gray-700">User</label>
        <select name="user_id" id="user_id" class="mt-1 block w-full" required>
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="book_id" class="block text-gray-700">Book</label>
        <select name="book_id" id="book_id" class="mt-1 block w-full" required>
            <option value="">Select Book</option>
            @foreach($books as $book)
                <option value="{{ $book->id }}" {{ $loan->book_id == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="due_date" class="block text-gray-700">Due Date</label>
        <input type="date" name="due_date" id="due_date" class="mt-1 block w-full" value="{{ $loan->due_date->format('Y-m-d') }}" required>
    </div>

    <div class="mb-4">
        <label for="returned_date" class="block text-gray-700">Returned Date</label>
        <input type="date" name="returned_date" id="returned_date" class="mt-1 block w-full" value="{{ $loan->returned_date ? $loan->returned_date->format('Y-m-d') : '' }}">
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary">Update Loan</button>
    </div>
</form>
@endsection
