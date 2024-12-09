@extends('layouts.app')

@section('title', 'Create Loan')

@section('content')
<h1>Create Loan</h1>
<form action="{{ route('admin.loans.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="user_id">User</label>
        <select name="user_id" id="user_id" class="form-control" required>
            <option value="">Select a User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="book_id">Book</label>
        <select name="book_id" id="book_id" class="form-control" required>
            <option value="">Select a Book</option>
            @foreach($books as $book)
            <option value="{{ $book->id }}">{{ $book->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="due_date">Due Date</label>
        <input type="date" name="due_date" id="due_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Create</button>
</form>
@endsection
