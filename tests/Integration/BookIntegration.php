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
    public function it_should_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/books/{$book->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}