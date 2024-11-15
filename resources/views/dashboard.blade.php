<!-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4>Total Books</h4>
                    <p class="h1">{{ $bookCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4>Total Users</h4>
                    <p class="h1">{{ $userCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4>Active Loans</h4>
                    <p class="h1">{{ $loanCount }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <a href="{{ route('books.index') }}" class="btn btn-primary w-100">Manage Books</a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('users.index') }}" class="btn btn-success w-100">Manage Users</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <a href="{{ route('loans.index') }}" class="btn btn-warning w-100">View Loans</a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('transactions.index') }}" class="btn btn-danger w-100">View Transactions</a>
        </div>
    </div>
</div>
@endsection -->
