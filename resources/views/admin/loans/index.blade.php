@extends('layouts.app')

@section('title', 'Manage Loans')

@section('content')
<h1>Manage Loans</h1>
<a href="{{ route('admin.loans.create') }}" class="btn btn-primary mb-4">Create Loan</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Book</th>
            <th>Due Date</th>
            <th>Returned Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loans as $loan)
        <tr>
            <td>{{ $loan->id }}</td>
            <td>{{ $loan->user->name }}</td>
            <td>{{ $loan->book->title }}</td>
            <td>{{ $loan->due_date }}</td>
            <td>{{ $loan->returned_date ?? 'Not Returned' }}</td>
            <td>
                <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
