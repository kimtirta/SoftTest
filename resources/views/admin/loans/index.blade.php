@extends('layouts.app')

@section('title', 'Loans Management')

@section('content')
<div class="container mt-4">
    <!-- Create New Loan Button -->
    <a href="{{ route('admin.loans.create') }}" class="btn btn-outline-success ">Create New Loan</a>
    <!-- Link to View Loans Returned -->
    <a href="{{ route('admin.loans.returned') }}" class="btn btn-outline-primary ">View Loans Returned</a>
    
    <!-- Section for Loans Not Returned -->
    <h1 class="text-center">Loans Not Returned</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Loans Not Returned Table -->
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
                    <th>Owed Money</th> <!-- New Column for Owed Money -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loans as $loan)
                    @php
                        // Calculate owed money if the loan is overdue
                        $dueDate = \Carbon\Carbon::parse($loan->due_date);
                        $currentDate = \Carbon\Carbon::now();
                        $daysOverdue = $dueDate->diffInDays($currentDate, false);
                        $owedMoney = ($daysOverdue > 0) ? $daysOverdue * 5000 : 0;
                    @endphp
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') }}</td>
                        <td class="text-danger text-center">Not Returned Yet</td>
                        <td class="text-center">
                            @if($owedMoney > 0)
                                Rp {{ number_format($owedMoney, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this loan?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            <!-- Pagination for Loans Not Returned -->
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection
