@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-center">User Dashboard</h1>

    <!-- Books Borrowed Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Books Borrowed</h2>

        @if($loans->isEmpty())
            <p class="text-gray-500">You have not borrowed any books yet.</p>
        @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">Book Title</th>
                        <th class="border px-4 py-2 text-left">Due Date</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Fees</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                        <tr class="hover:bg-gray-50">
                            <!-- Book Title -->
                            <td class="border px-4 py-2">{{ $loan->book->title }}</td>

                            <!-- Due Date -->
                            <td class="border px-4 py-2">{{ $loan->due_date->format('F j, Y') }}</td>

                            <!-- Status: Returned or Not Returned -->
                            <td class="border px-4 py-2">
                                @if($loan->returned_date)
                                    <span class="text-green-500 font-semibold">Returned on {{ $loan->returned_date->format('F j, Y') }}</span>
                                @else
                                    <span class="text-red-500 font-semibold">Not Returned Yet</span>
                                @endif
                            </td>

                            <!-- Transaction Details -->
                            <td class="border px-4 py-2">
                                @if($loan->transaction)
                                    @if($loan->transaction->fine_amount > 0)
                                        Fine: <strong>Rp.{{ number_format($loan->transaction->fine_amount) }}</strong><br>
                                    @endif
                                    @if($loan->transaction->paid)
                                        <span class="text-green-500">Paid</span>
                                    @else
                                        <span class="text-yellow-500">Pending Payment</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    </div>
</div>
@endsection
