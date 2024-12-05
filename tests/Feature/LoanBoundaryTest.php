<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanBoundaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function loan_due_date_cannot_be_in_the_past()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->post(route('loans.store'), [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->subDay()->toDateString(), // Invalid past date
        ]);

        $response->assertSessionHasErrors('due_date');
    }

    /** @test */
    public function loan_due_date_should_be_a_valid_date()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->post(route('loans.store'), [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => 'invalid-date', // Invalid date
        ]);

        $response->assertSessionHasErrors('due_date');
    }
}
