<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanConsistencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function two_loans_for_the_same_book_and_user_should_not_be_allowed()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $this->post(route('loans.store'), [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(7)->toDateString(),
        ]);

        $response = $this->post(route('loans.store'), [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertSessionHasErrors('book_id');
    }
}
