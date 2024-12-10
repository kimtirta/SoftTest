@extends('layouts.app')

@section('title', 'Edit Loan')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Edit Loan</h1>
    <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                <option value="" disabled>Select a user</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $loan->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="book_id" class="form-label">Book</label>
            <select name="book_id" id="book_id" class="form-select" required>
                <option value="" disabled>Select a book</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ $loan->book_id == $book->id ? 'selected' : '' }}>
                        {{ $book->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $loan->due_date->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="returned_date" class="form-label">Returned Date</label><br>
            <!-- Radio Buttons for Returned Status -->
            <label>
                <input type="radio" name="returned_status" value="returned" 
                       {{ $loan->returned_date ? 'checked' : '' }}>
                Returned by {{ $loan->returned_date ? $loan->returned_date->format('M d, Y') : '' }}
            </label><br>
            <label>
                <input type="radio" name="returned_status" value="not_returned" 
                       {{ !$loan->returned_date ? 'checked' : '' }}>
                Not Returned
            </label><br>

            <!-- If "Returned" is selected, provide a field for the returned date -->
            <div id="returned_date_input" class="mt-2" style="display: {{ $loan->returned_date ? 'block' : 'none' }};">
                <label for="returned_date" class="form-label">Return Date</label>
                <input type="date" name="returned_date" id="returned_date" class="form-control" value="{{ $loan->returned_date ? $loan->returned_date->format('Y-m-d') : '' }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Loan</button>
    </form>
</div>

<script>
    // JavaScript to toggle the "Returned Date" input field
    document.querySelectorAll('input[name="returned_status"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            var returnedDateInput = document.getElementById('returned_date_input');
            if (document.querySelector('input[name="returned_status"]:checked').value === 'returned') {
                returnedDateInput.style.display = 'block';

                // Set today's date as the default returned date when 'Returned by' is selected
                if (!document.getElementById('returned_date').value) {
                    var today = new Date();
                    var dateString = today.toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
                    document.getElementById('returned_date').value = dateString;
                }
            } else {
                returnedDateInput.style.display = 'none';
            }
        });
    });

    // Initialize the state of the "Returned Date" field based on the current returned status
    document.addEventListener('DOMContentLoaded', function () {
        var returnedStatus = document.querySelector('input[name="returned_status"]:checked');
        if (returnedStatus && returnedStatus.value === 'returned') {
            document.getElementById('returned_date_input').style.display = 'block';
            // Set today's date as the default returned date when 'Returned by' is selected
            if (!document.getElementById('returned_date').value) {
                var today = new Date();
                var dateString = today.toISOString().split('T')[0];
                document.getElementById('returned_date').value = dateString;
            }
        }
    });
</script>

@endsection
