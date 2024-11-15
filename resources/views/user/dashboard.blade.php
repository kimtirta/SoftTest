@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<h1 class="text-3xl font-bold mb-4">User Dashboard</h1>
<div>
    <h2 class="text-xl font-bold mb-2">Books Borrowed</h2>
    <ul>
        @foreach($loans as $loan)
        <li>
            {{ $loan->book->title }} - Due: {{ $loan->due_date }}
            @if($loan->is_overdue)
                <span class="text-red-500">(Overdue)</span>
            @endif
        </li>
        @endforeach
    </ul>

    <div class="mt-6">
        <a href="{{ route('user.books.index') }}" class="text-white bg-blue-500 px-4 py-2 rounded">Borrow Book</a>
    </div>
</div>
@endsection
