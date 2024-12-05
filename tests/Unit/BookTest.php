<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_book()
    {
        $book = Book::create([
            'title' => 'Sample Book Title',
            'author' => 'Author Name',
            'genre' => 'Fiction',
            'synopsis' => 'This is a sample synopsis.',
            'available_copies' => 5,
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Sample Book Title',
            'author' => 'Author Name',
            'genre' => 'Fiction',
            'synopsis' => 'This is a sample synopsis.',
            'available_copies' => 5,
        ]);
    }

    /** @test */
    public function it_should_require_title()
    {
        $book = new Book();
        $validator = Validator::make($book->toArray(), $book->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The title field is required.', $validator->errors()->all());
    }

    /** @test */
    public function it_should_require_author()
    {
        $book = new Book();
        $validator = Validator::make($book->toArray(), $book->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The author field is required.', $validator->errors()->all());
    }

    /** @test */
    public function it_should_have_a_valid_genre()
    {
        $book = new Book();
        $book->genre = 'Invalid Genre'; // Assuming you have genre validation in the model
        
        $this->assertFalse($book->save());  // Assuming it should fail validation
    }

    /** @test */
    public function it_should_set_default_available_copies_if_not_set()
    {
        $book = Book::create([
            'title' => 'Default Copies Book',
            'author' => 'Author Name',
            'genre' => 'Non-Fiction',
            'synopsis' => 'A book with default copies.',
        ]);

        $this->assertEquals(0, $book->available_copies);
    }

    /** @test */
    public function it_should_belong_to_a_user()
    {
        $user = User::factory()->create();
        $book = Book::create([
            'title' => 'Book with User',
            'author' => 'Some Author',
            'genre' => 'Sci-Fi',
            'synopsis' => 'Synopsis goes here.',
            'available_copies' => 3,
            'user_id' => $user->id, // Assuming `user_id` is a foreign key in books
        ]);

        $this->assertInstanceOf(User::class, $book->user);
    }

    /** @test */
    public function it_should_have_many_loans()
    {
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'book_id' => $book->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertCount(1, $book->loans);
        $this->assertInstanceOf(Loan::class, $book->loans->first());
    }

    /** @test */
    public function it_should_not_allow_negative_available_copies()
    {
        $book = new Book([
            'title' => 'Invalid Copies Book',
            'author' => 'Some Author',
            'genre' => 'Fantasy',
            'synopsis' => 'A book with negative copies.',
            'available_copies' => -5,
        ]);

        $this->assertFalse($book->save());  // Should not save to the database
    }

    /** @test */
    public function it_should_return_a_book_to_loan_when_available_copies_are_greater_than_0()
    {
        $book = Book::create([
            'title' => 'Loanable Book',
            'author' => 'Famous Author',
            'genre' => 'Thriller',
            'synopsis' => 'A book available for loan.',
            'available_copies' => 3,
        ]);

        // Simulate a loan action
        $book->decrement('available_copies');

        $this->assertEquals(2, $book->available_copies);
    }

    /** @test */
    public function it_should_return_true_if_book_is_available_for_loan()
    {
        $book = Book::create([
            'title' => 'Available Book',
            'author' => 'Some Author',
            'genre' => 'Drama',
            'synopsis' => 'A book available for loan.',
            'available_copies' => 1,
        ]);

        $this->assertTrue($book->isAvailableForLoan());
    }

    /** @test */
    public function it_should_return_false_if_book_is_not_available_for_loan()
    {
        $book = Book::create([
            'title' => 'Unavailable Book',
            'author' => 'Some Author',
            'genre' => 'Horror',
            'synopsis' => 'A book that is unavailable.',
            'available_copies' => 0,
        ]);

        $this->assertFalse($book->isAvailableForLoan());
    }

    /** @test */
    public function it_should_validate_boundary_for_available_copies()
    {
        // Test for upper boundary
        $book = new Book([
            'title' => 'High Copies Book',
            'author' => 'Max Author',
            'genre' => 'Adventure',
            'synopsis' => 'A book with max available copies.',
            'available_copies' => 1000000,  // Arbitrary large number
        ]);

        $this->assertTrue($book->save()); // Ensure it saves successfully

        // Test for lower boundary
        $book = new Book([
            'title' => 'Zero Copies Book',
            'author' => 'Zero Author',
            'genre' => 'Drama',
            'synopsis' => 'A book with zero copies.',
            'available_copies' => 0,  // Edge case for zero copies
        ]);

        $this->assertTrue($book->save()); // Ensure it saves successfully
    }
}
