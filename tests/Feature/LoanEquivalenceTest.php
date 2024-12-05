<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanEquivalenceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_valid_loan_should_pass()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->post(route('loans.store'), [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect(route('loans.index'));
        $this->assertDatabaseHas('loans', ['user_id' => $user->id, 'book_id' => $book->id]);
    }

    /** @test */
    public function creating_a_loan_without_due_date_should_fail()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->post(route('loans.store'), [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => '',
        ]);

        $response->assertSessionHasErrors('due_date');
    }
}
