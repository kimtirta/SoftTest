<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 5]);

        $response = $this->postJson('/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(7),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    /** @test */
    public function it_should_fail_if_book_is_unavailable()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 0]);

        $response = $this->postJson('/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(7),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('book_id');
    }

    /** @test */
    public function it_should_return_a_loaned_book()
    {
        $loan = Loan::factory()->create([
            'returned_date' => null,
        ]);

        $response = $this->putJson("/loans/{$loan->id}/return");

        $response->assertStatus(200);
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'returned_date' => now()->toDateString(),
        ]);
    }
}