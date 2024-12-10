@extends('layouts.app')

@section('title', 'Return Requests')

@section('content')
<div class="container mt-4">
    <h1 class="text-center">Return Requests</h1>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Loan ID</th>
                <th>User</th>
                <th>Book</th>
                <th>Due Date</th>
                <th>Returned Date</th>
                <th>Fine Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returnRequests as $loan)
            <tr>
                <td>{{ $loan->id }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->due_date }}</td>
                <td>{{ $loan->returned_date }}</td>
                <td>{{ $loan->transaction->fine_amount }}</td>
                <td>
                    <form action="{{ route('admin.loans.approveReturn', $loan->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm">Approve</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No return requests</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
