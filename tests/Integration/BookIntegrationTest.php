<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_book()
    {
        $response = $this->postJson('/books', [
            'title' => 'A Great Book',
            'author' => 'John Doe',
            'genre' => 'Fiction',
            'available_copies' => 5,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', ['title' => 'A Great Book']);
    }

    /** @test */
    public function it_should_fail_if_title_is_missing()
    {
        $response = $this->postJson('/books', [
            'author' => 'John Doe',
            'genre' => 'Fiction',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_should_fail_if_available_copies_is_invalid()
    {
        $response = $this->postJson('/books', [
            'title' => 'Invalid Copies',
            'author' => 'John Doe',
            'genre' => 'Fiction',
            'available_copies' => -1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('available_copies');
    }

    /** @test */
    public function it_should_list_all_books()
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/books');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_should_get_details_of_a_single_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'genre' => $book->genre,
                'available_copies' => $book->available_copies,
            ],
        ]);
    }

    /** @test */
    public function it_should_return_404_if_book_not_found()
    {
        $response = $this->getJson('/books/999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Book not found.',
        ]);
    }

    /** @test */
    public function it_should_update_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->putJson("/books/{$book->id}", [
            'title' => 'Updated Book Title',
            'author' => 'Jane Doe',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['title' => 'Updated Book Title']);
    }

    /** @test */
    public function it_should_fail_to_update_a_book_if_title_is_missing()
    {
        $book = Book::factory()->create();

        $response = $this->putJson("/books/{$book->id}", [
            'author' => 'Jane Doe',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_should_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/books/{$book->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /** @test */
    public function it_should_fail_to_delete_a_nonexistent_book()
    {
        $response = $this->deleteJson('/books/999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Book not found.',
        ]);
    }

    /** @test */
    public function it_should_search_books_by_title()
    {
        Book::factory()->create(['title' => 'The Great Gatsby']);
        Book::factory()->create(['title' => 'Great Expectations']);
        Book::factory()->create(['title' => 'A Random Book']);

        $response = $this->getJson('/books?search=Great');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    
}