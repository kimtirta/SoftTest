@extends('layouts.app')

@section('title', 'My Loans')

@section('content')
<div class="container mt-4">
    <h1 class="text-center">My Loans</h1>
    
    <h3 class="mt-4">Active Loans</h3>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Book</th>
                <th>Due Date</th>
                <th>Fine Amount</th>
                <th>Request Return</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activeLoans as $loan)
            <tr>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->due_date }}</td>
                <td>{{ $loan->transaction->fine_amount }}</td>
                <td>
                    <form action="{{ route('users.loans.returnRequest', $loan->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-warning btn-sm">Request Return</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No active loans</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Past Loans Section -->
    <h3 class="mt-4">Past Loans</h3>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Book</th>
                <th>Due Date</th>
                <th>Returned Date</th>
                <th>Fine Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pastLoans as $loan)
            <tr>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->due_date }}</td>
                <td>{{ $loan->returned_date }}</td>
                <td>{{ $loan->transaction->fine_amount }}</td>
                <td>{{ $loan->transaction->paid ? 'Paid' : 'Unpaid' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No past loans</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
