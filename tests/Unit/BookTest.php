<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test valid book entry.
     */
    public function test_valid_book_entry()
    {
        $book = Book::factory()->create([
            'title' => 'The Great Book',
            'author' => 'John Doe',
            'genre' => 'Fiction',
            'available_copies' => 5,
        ]);

        $this->assertTrue($book->isAvailable);
        $this->assertDatabaseHas('books', [
            'title' => 'The Great Book',
            'author' => 'John Doe',
            'genre' => 'Fiction',
            'available_copies' => 5,
        ]);
    }

    /**
     * Test book entry with available copies set to zero.
     */
    public function test_book_with_zero_available_copies()
    {
        $book = Book::factory()->create([
            'available_copies' => 0,
        ]);

        $this->assertFalse($book->isAvailable);
    }

    /**
     * Test book entry with negative available copies.
     */
    public function test_book_with_negative_available_copies()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'available_copies' => -5,
        ]);
    }

    /**
     * Test book entry without a title.
     */
    public function test_book_missing_title()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'title' => null,
        ]);
    }

    /**
     * Test book entry without an author.
     */
    public function test_book_missing_author()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'author' => null,
        ]);
    }

    /**
     * Test book entry with a valid genre.
     */
    public function test_book_with_valid_genre()
    {
        $book = Book::factory()->create([
            'genre' => 'Mystery',
        ]);

        $this->assertDatabaseHas('books', [
            'genre' => 'Mystery',
        ]);
    }

    /**
     * Test book entry with synopsis field not in fillable array.
     */
    public function test_book_with_synopsis()
    {
        $book = Book::factory()->create([
            'synopsis' => 'A thrilling mystery novel.',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => $book->title,
            'author' => $book->author,
        ]);
    }

    /**
     * Test maximum boundary for available copies.
     */
    public function test_maximum_available_copies()
    {
        $book = Book::factory()->create([
            'available_copies' => 10,
        ]);

        $this->assertTrue($book->isAvailable);
    }

    /**
     * Test available copies slightly above maximum boundary.
     */
    public function test_above_maximum_available_copies()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'available_copies' => 11,
        ]);
    }

    /**
     * Test minimum boundary for available copies.
     */
    public function test_minimum_available_copies()
    {
        $book = Book::factory()->create([
            'available_copies' => 1,
        ]);

        $this->assertTrue($book->isAvailable);
    }

    /**
     * Test book entry with non-string title.
     */
    public function test_non_string_title()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'title' => 123,
        ]);
    }

    /**
     * Test book entry with non-string author.
     */
    public function test_non_string_author()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'author' => 123,
        ]);
    }

    /**
     * Test book entry with non-integer available copies.
     */
    public function test_non_integer_available_copies()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'available_copies' => 3.5,
        ]);
    }

    /**
     * Test book entry with empty title and author.
     */
    public function test_empty_title_and_author()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Book::factory()->create([
            'title' => '',
            'author' => '',
        ]);
    }

    /**
     * Test book entry with empty genre field.
     */
    public function test_empty_genre_field()
    {
        $book = Book::factory()->create([
            'genre' => '',
        ]);

        $this->assertDatabaseHas('books', [
            'genre' => '',
        ]);
    }
}
