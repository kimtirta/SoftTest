<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
        ]);

        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => $loan->due_date->toDateString(),
        ]);
    }

    /** @test */
    public function it_should_require_user_id()
    {
        $loan = new Loan();
        $validator = Validator::make($loan->toArray(), $loan->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The user id field is required.', $validator->errors()->all());
    }

    /** @test */
    public function it_should_require_book_id()
    {
        $loan = new Loan();
        $validator = Validator::make($loan->toArray(), $loan->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The book id field is required.', $validator->errors()->all());
    }

    /** @test */
    public function it_should_require_due_date()
    {
        $loan = new Loan();
        $validator = Validator::make($loan->toArray(), $loan->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The due date field is required.', $validator->errors()->all());
    }

    /** @test */
    public function it_should_belong_to_a_user()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
        ]);

        $this->assertInstanceOf(User::class, $loan->user);
    }

    /** @test */
    public function it_should_belong_to_a_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
        ]);

        $this->assertInstanceOf(Book::class, $loan->book);
    }

    /** @test */
    public function it_should_allow_return_of_book_on_time()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
            'returned_date' => null,
        ]);

        $loan->returnBook();

        $this->assertNotNull($loan->returned_date);
        $this->assertTrue($loan->returned_date <= now());
    }

    /** @test */
    public function it_should_mark_book_as_overdue_if_returned_after_due_date()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->subDays(2),
            'returned_date' => now()->subDays(1),
        ]);

        $this->assertTrue($loan->isOverdue());
    }

    /** @test */
    public function it_should_not_allow_multiple_loans_for_the_same_book_to_the_same_user()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
        ]);

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
        ]);

        $this->assertDatabaseCount('loans', 1); // Only 1 loan should be allowed
    }

    /** @test */
    public function it_should_not_allow_loan_with_invalid_due_date()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loan = new Loan([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => 'invalid-date',
        ]);

        $this->assertFalse($loan->save()); // It should not save due to invalid date
    }

    /** @test */
    public function it_should_return_true_if_book_is_on_time()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
        ]);

        $this->assertTrue($loan->isOnTime());
    }

    /** @test */
    public function it_should_return_false_if_book_is_not_on_time()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->subDays(2),
            'returned_date' => now()->subDays(1),
        ]);

        $this->assertFalse($loan->isOnTime());
    }

    /** @test */
    public function it_should_handle_overdue_books()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->subDays(3),
            'returned_date' => null,
        ]);

        $this->assertTrue($loan->isOverdue());
    }

    /** @test */
    public function it_should_handle_edge_case_for_due_date_at_midnight()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->startOfDay(),
            'returned_date' => now()->startOfDay(),
        ]);

        $this->assertFalse($loan->isOverdue());
    }
}
