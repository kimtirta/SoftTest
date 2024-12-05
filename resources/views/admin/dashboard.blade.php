@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold">Total Users</h2>
        <p class="text-3xl font-bold">{{ $total_users }}</p> <!-- Use the total_users variable -->
        <a href="{{ route('users.index') }}" class="text-blue-500 mt-2 inline-block">Manage Users</a> <!-- Updated the route -->
    </div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold">Total Books</h2>
        <p class="text-3xl font-bold">{{ $books->count() }}</p>
        <a href="{{ route('books.index') }}" class="text-blue-500 mt-2 inline-block">Manage Books</a>
        </div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold">Active Loans</h2>
        <p class="text-3xl font-bold">{{ $loans->count() }}</p>
        <a href="{{ route('admin.loans.index') }}" class="text-blue-500 mt-2 inline-block">View All Loans</a>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-2xl font-semibold mb-4">Recent Loans</h2>
    <table class="min-w-full bg-white">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-2 px-4">User</th>
                <th class="py-2 px-4">Book</th>
                <th class="py-2 px-4">Due Date</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
                <tr class="border-b">
                    <td class="py-2 px-4">{{ $loan->user->name }}</td>
                    <td class="py-2 px-4">{{ $loan->book->title }}</td>
                    <td class="py-2 px-4">{{ $loan->due_date->format('M d, Y') }}</td>
                    <td class="py-2 px-4">
                        @if($loan->is_overdue)
                            <span class="text-red-500 font-semibold">Overdue</span>
                        @else
                            <span class="text-green-500 font-semibold">On Time</span>
                        @endif
                    </td>
                    <td class="py-2 px-4">
                        <a href="{{ route('admin.loans.return', $loan->id) }}" class="text-blue-500 mr-2">Mark as Returned</a>
                        <a href="{{ route('admin.loans.edit', $loan->id) }}" class="text-blue-500 mr-2">Edit</a>
                        <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
