@extends('layouts.app')

@section('title', 'Loans Returned')

@section('content')
<div class="container mt-4">
    <h1 class="text-center">Loans Returned</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Go Back Button -->
    <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary mb-4">Go Back to Loans Not Returned</a>

    <!-- Loans Returned Table -->
    @if($pastLoans->isEmpty())
        <div class="alert alert-info text-center">
            No loans to display.
        </div>
    @else
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Book</th>
                    <th>Due Date</th>
                    <th>Returned Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pastLoans as $loan)
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->returned_date)->format('M d, Y') }}</td>
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
        <div class="d-flex justify-content-center mt-4">
            <!-- Pagination for Loans Returned -->
            {{ $pastLoans->links() }}
        </div>
    @endif
</div>
@endsection
