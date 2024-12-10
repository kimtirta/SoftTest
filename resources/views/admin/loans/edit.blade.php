@extends('layouts.app')

@section('title', 'Edit Loan')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Edit Loan</h1>
    <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="" disabled>Select a user</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="book_id" class="form-label">Book</label>
            <select name="book_id" id="book_id" class="form-select" required>
                <option value="" disabled>Select a book</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ $loan->book_id == $book->id ? 'selected' : '' }}>
                        {{ $book->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $loan->due_date->format('Y-m-d') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Loan</button>
    </form>
</div>
@endsection
