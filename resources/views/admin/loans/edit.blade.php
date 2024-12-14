@extends('layouts.app')

@section('title', 'Edit Loan')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Edit Loan</h1>
    <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- User Selection -->
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

        <!-- Book Selection -->
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

        <!-- Due Date -->
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $loan->due_date->format('Y-m-d') }}" required>
        </div>

        <!-- Returned Date -->
        <div class="mb-3">
            <label for="returned_date" class="form-label">Returned Date</label><br>
            <label>
                <input type="radio" name="returned_status" value="returned" {{ $loan->returned_date ? 'checked' : '' }}>
                Returned
            </label><br>
            <label>
                <input type="radio" name="returned_status" value="not_returned" {{ !$loan->returned_date ? 'checked' : '' }}>
                Not Returned
            </label>
            <div id="returned_date_input" class="mt-2" style="display: {{ $loan->returned_date ? 'block' : 'none' }};">
                <input type="date" name="returned_date" id="returned_date" class="form-control" value="{{ $loan->returned_date ? $loan->returned_date->format('Y-m-d') : '' }}">
            </div>
        </div>

        <!-- Fine Amount -->
        <div class="mb-3">
            <label for="fine_amount" class="form-label">Fine Amount (Rp)</label>
            <input type="number" name="fine_amount" id="fine_amount" class="form-control" 
                   value="{{ $loan->transaction->fine_amount ?? 0 }}" step="1000" min="0">
        </div>

        <!-- Paid Status -->
<div class="mb-3">
    <label class="form-label">Paid Status</label><br>
    <div class="form-check form-check-inline">
        <input type="radio" name="paid" id="paid_no" value="0" class="form-check-input"
            {{ $loan->transaction && !$loan->transaction->paid ? 'checked' : '' }}>
        <label class="form-check-label" for="paid_no">Not Paid</label>
    </div>
    <div class="form-check form-check-inline">
        <input type="radio" name="paid" id="paid_yes" value="1" class="form-check-input"
            {{ $loan->transaction && $loan->transaction->paid ? 'checked' : '' }}>
        <label class="form-check-label" for="paid_yes">Paid</label>
    </div>
</div>


        <!-- Submit Button -->
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
                if (!document.getElementById('returned_date').value) {
                    var today = new Date();
                    document.getElementById('returned_date').value = today.toISOString().split('T')[0];
                }
            } else {
                returnedDateInput.style.display = 'none';
            }
        });
    });
</script>
@endsection
