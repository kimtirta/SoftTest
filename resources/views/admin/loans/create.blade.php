@extends('layouts.app')

@section('title', 'Add New Loan')

@section('content')
<form action="{{ route('admin.loans.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label for="user_id" class="block text-gray-700">User</label>
        <select name="user_id" id="user_id" class="mt-1 block w-full" required>
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="book_id" class="block text-gray-700">Book</label>
        <select name="book_id" id="book_id" class="mt-1 block w-full" required>
            <option value="">Select Book</option>
            @foreach($books as $book)
                <option value="{{ $book->id }}">{{ $book->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="due_date" class="block text-gray-700">Due Date</label>
        <input type="date" name="due_date" id="due_date" class="mt-1 block w-full" required>
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary">Save Loan</button>
    </div>
</form>
@endsection
