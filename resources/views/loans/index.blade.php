@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Borrowed Books</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Author</th>
                <th>Borrower</th>
                <th>Due Date</th>
                <th>Returned Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->book->author }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->due_date }}</td>
                <td>{{ $loan->returned_date ?? 'Not Returned' }}</td>
                <td>
                    @if (!$loan->returned_date)
                    <form action="{{ route('loans.return', $loan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Mark as Returned</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
