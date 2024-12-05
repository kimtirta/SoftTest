<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_update_a_book()
    {
        $book = Book::factory()->create();

        $updatedData = [
            'title' => 'The Great Gatsby (Updated)',
            'author' => 'F. Scott Fitzgerald',
            'genre' => 'Fiction',
            'synopsis' => 'An updated synopsis.',
        ];

        $response = $this->put(route('books.update', $book->id), $updatedData);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', $updatedData);
    }
}
