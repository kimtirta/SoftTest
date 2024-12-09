@extends('layouts.app')

@section('title', 'Edit Loan')

@section('content')
<h1>Edit Loan</h1>
<form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="user_id">User</label>
        <select name="user_id" id="user_id" class="form-control" required>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="book_id">Book</label>
        <select name="book_id" id="book_id" class="form-control" required>
            @foreach($books as $book)
            <option value="{{ $book->id }}" {{ $loan->book_id == $book->id ? 'selected' : '' }}>
                {{ $book->title }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="due_date">Due Date</label>
        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $loan->due_date }}" required>
    </div>
    <div class="form-group">
        <label for="returned_date">Returned Date</label>
        <input type="date" name="returned_date" id="returned_date" class="form-control" value="{{ $loan->returned_date }}">
    </div>
    <button type="submit" class="btn btn-warning">Update</button>
</form>
@endsection
