@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Transaction Fines</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Borrower</th>
                <th>Book Title</th>
                <th>Fine Amount</th>
                <th>Paid Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaction->loan->user->name }}</td>
                <td>{{ $transaction->loan->book->title }}</td>
                <td>Rp {{ number_format($transaction->fine_amount, 2) }}</td>
                <td>{{ $transaction->paid ? 'Paid' : 'Unpaid' }}</td>
                <td>
                    @if (!$transaction->paid)
                    <form action="{{ route('transactions.markAsPaid', $transaction->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Mark as Paid</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
