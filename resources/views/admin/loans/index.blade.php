@extends('layouts.app')

@section('title', 'Loans Management')

@section('content')
<div class="container mt-4">
    <!-- Create New Loan Button -->
    <a href="{{ route('admin.loans.create') }}" class="btn btn-outline-success mb-3">Create New Loan</a>
    
    <!-- Page Header -->
    <h1 class="text-center my-4">All Loans</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Loans Table -->
    @if($loans->isEmpty())
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
                    <th>Status</th>
                    <th>Paid</th>
                    <th>Late Fees</th>
                    <th>Actions</th>
                    <th>Return</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loans as $loan)
                    @php
                        $dueDate = \Carbon\Carbon::parse($loan->due_date);
                        $currentDate = \Carbon\Carbon::now();
                        $daysOverdue = $dueDate->diffInDays($currentDate, false);
                        $owedMoney = ($daysOverdue > 0 && !$loan->returned_date) ? $daysOverdue * 5000 : 0;
                    @endphp
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $dueDate->format('M d, Y') }}</td>
                        <td class="text-center">
                            @if($loan->returned_date)
                                <span class="text-success">Returned</span>
                            @else
                                <span class="text-danger">Not Returned</span>
                            @endif
                        </td>
                        <!-- Owed Money -->
<!-- Mark as Paid -->
<td class="text-center">
    @if($loan->transaction && !$loan->transaction->paid)
        <form action="{{ route('admin.loans.paid', $loan->id) }}" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Mark the fine as paid?');">
                Mark as Paid
            </button>
        </form>
    @elseif($loan->transaction && $loan->transaction->paid)
        <span class="badge bg-success">Paid</span>
    @else
        <span class="text-muted">No Fine</span>
    @endif
   
</td>
<td class="text-center">
<!-- Display Fine Amount or "-" if it doesn't exist -->
<div class="mt-2">
        @if($loan->transaction && $loan->transaction->fine_amount > 0)
            Rp {{ number_format($loan->transaction->fine_amount, 0, ',', '.') }}
        @else
            -
        @endif
</td>
<!-- Actions -->
<td class="text-center">
    <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" class="d-inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this loan?');">Delete</button>
    </form>
</td>

<!-- Mark as Returned -->
<td class="text-center">
    @if(!$loan->returned_date)
        <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this loan as returned?');">
                Mark as Returned
            </button>
        </form>
    @else
        <span class="badge bg-secondary">Returned</span>
    @endif
</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection
