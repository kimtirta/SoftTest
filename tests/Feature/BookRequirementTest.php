<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function book_create_functionality_meets_specified_requirements()
    {
        $bookData = [
            'title' => 'The Catcher in the Rye',
            'author' => 'J.D. Salinger',
            'genre' => 'Fiction',
            'synopsis' => 'A book about a young man coming of age.',
            'available_copies' => 10,
        ];

        $response = $this->post(route('books.store'), $bookData);

        // Ensure that a book with the required fields is stored correctly
        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', $bookData);
    }

    /** @test */
    public function book_should_have_non_empty_author_and_title()
    {
        $response = $this->post(route('books.store'), [
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'genre' => 'Fiction',
            'synopsis' => 'A story about the American dream.',
            'available_copies' => 5,
        ]);

        $response->assertSessionDoesNotHaveErrors();
        $this->assertDatabaseHas('books', [
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald'
        ]);
    }
}
