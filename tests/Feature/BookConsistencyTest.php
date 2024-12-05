<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookConsistencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_two_books_with_same_data_should_create_two_records()
    {
        $bookData = [
            'title' => 'Unique Book Title',
            'author' => 'Some Author',
            'genre' => 'Fiction',
            'synopsis' => 'Test description for book.',
            'available_copies' => 10,
        ];

        // Create first book
        $this->post(route('books.store'), $bookData);

        // Create second book with same data
        $this->post(route('books.store'), $bookData);

        // Both books should exist in the database
        $this->assertDatabaseCount('books', 2);
    }

    /** @test */
    public function book_data_consistency_is_maintained_after_update()
    {
        $book = Book::factory()->create();

        $updatedData = [
            'title' => 'Updated Book Title',
            'author' => 'Updated Author',
            'genre' => 'Non-Fiction',
            'synopsis' => 'Updated description of the book.',
        ];

        $this->put(route('books.update', $book->id), $updatedData);

        // Ensure the updated data is consistent
        $this->assertDatabaseHas('books', $updatedData);
    }
}
