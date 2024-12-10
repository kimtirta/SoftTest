@extends('layouts.app')

@section('title', 'All Loans')

@section('content')
<div class="container mt-4">
    <h1 class="text-center">All Loans</h1>
    <a href="{{ route('admin.loans.create') }}" class="btn btn-primary mb-4">Create New Loan</a>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Book</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
                <tr>
                    <td>{{ $loan->user->name }}</td>
                    <td>{{ $loan->book->title }}</td>
                    <td>{{ $loan->due_date->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $loans->links() }}
</div>
@endsection
