<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookExploratoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function book_create_functionality_should_handle_special_characters_in_title()
    {
        $response = $this->post(route('books.store'), [
            'title' => '@!#%Book Title$%',
            'author' => 'Author Name',
            'genre' => 'Mystery',
            'synopsis' => 'A mysterious tale.',
            'available_copies' => 5,
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'title' => '@!#%Book Title$%',
        ]);
    }

    /** @test */
    public function book_create_should_handle_long_title_and_author()
    {
        $response = $this->post(route('books.store'), [
            'title' => str_repeat('A', 300), // Very long title
            'author' => str_repeat('B', 300), // Very long author name
            'genre' => 'Fiction',
            'synopsis' => 'Test book with long title and author.',
            'available_copies' => 5,
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('author');
    }
}
