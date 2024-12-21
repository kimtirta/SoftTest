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
        $book = Book::factory()->create(['available_copies' => 3]);

        $response = $this->postJson('/api/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'user_id', 'book_id', 'due_date']);
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
        $this->assertDatabaseHas('books', ['available_copies' => 2]);
    }

    /** @test */
    public function it_should_fail_if_no_books_available()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 0]);

        $response = $this->postJson('/api/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('book_id');
    }

    /** @test */
    public function it_should_fail_if_due_date_is_missing()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available_copies' => 3]);

        $response = $this->postJson('/api/loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('due_date');
    }

    /** @test */
    public function it_should_return_a_book()
    {
        $loan = Loan::factory()->create(['returned_date' => null]);
        $book = $loan->book;

        $response = $this->putJson("/api/loans/{$loan->id}/return");

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'returned_date']);
        $this->assertDatabaseHas('loans', ['id' => $loan->id, 'returned_date' => now()->toDateString()]);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'available_copies' => $book->available_copies + 1]);
    }

    /** @test */
    public function it_should_fail_if_returning_already_returned_book()
    {
        $loan = Loan::factory()->create(['returned_date' => now()->toDateString()]);

        $response = $this->putJson("/api/loans/{$loan->id}/return");

        $response->assertStatus(400)
                 ->assertJson([
                     'message' => 'The book has already been returned.',
                 ]);
    }

    /** @test */
    public function it_should_list_all_loans()
    {
        Loan::factory()->count(5)->create();

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_should_get_details_of_a_single_loan()
    {
        $loan = Loan::factory()->create();

        $response = $this->getJson("/api/loans/{$loan->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $loan->id,
                         'user_id' => $loan->user_id,
                         'book_id' => $loan->book_id,
                         'due_date' => $loan->due_date,
                         'returned_date' => $loan->returned_date,
                     ],
                 ]);
    }

    /** @test */
    public function it_should_fail_to_get_nonexistent_loan()
    {
        $response = $this->getJson('/api/loans/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Loan not found.',
                 ]);
    }

    /** @test */
    public function it_should_delete_a_loan()
    {
        $loan = Loan::factory()->create();

        $response = $this->deleteJson("/api/loans/{$loan->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }

    /** @test */
    public function it_should_fail_to_delete_nonexistent_loan()
    {
        $response = $this->deleteJson('/api/loans/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Loan not found.',
                 ]);
    }
}