@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    @foreach($users as $user)
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h3 class="text-xl font-semibold">{{ $user->name }}</h3>
        <p class="text-gray-500"><strong>Email:</strong> {{ $user->email }}</p>

        <div class="mt-4">
            <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500">Edit</a>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

{{ $users->links() }}  <!-- Pagination links -->
@endsection
