@extends('layouts.app')

@section('title', 'Manage Loans')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md mb-4">
    <a href="{{ route('admin.loans.create') }}" class="btn btn-primary">Add New Loan</a>
</div>

<div class="mt-8">
    <h2 class="text-2xl font-semibold mb-4">Loan List</h2>
    <table class="min-w-full bg-white">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-2 px-4">User</th>
                <th class="py-2 px-4">Book</th>
                <th class="py-2 px-4">Due Date</th>
                <th class="py-2 px-4">Returned Date</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
                <tr class="border-b">
                    <td class="py-2 px-4">{{ $loan->user->name }}</td>
                    <td class="py-2 px-4">{{ $loan->book->title }}</td>
                    <td class="py-2 px-4">{{ $loan->due_date->format('M d, Y') }}</td>
                    <td class="py-2 px-4">{{ $loan->returned_date ? $loan->returned_date->format('M d, Y') : 'Not Returned' }}</td>
                    <td class="py-2 px-4">
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

    <div class="mt-4">
        {{ $loans->links() }}
    </div>
</div>
@endsection
