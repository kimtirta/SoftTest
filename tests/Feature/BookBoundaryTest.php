<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookBoundaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function book_title_cannot_be_empty()
    {
        $response = $this->post(route('books.store'), [
            'title' => '',
            'author' => 'Some Author',
            'genre' => 'Fiction',
            'synopsis' => 'Test book description.',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function book_title_max_length_is_255_characters()
    {
        $response = $this->post(route('books.store'), [
            'title' => str_repeat('A', 256), // One more than max
            'author' => 'Some Author',
            'genre' => 'Fiction',
            'synopsis' => 'Test book description.',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function book_available_copies_should_not_be_negative()
    {
        $response = $this->post(route('books.store'), [
            'title' => 'Valid Title',
            'author' => 'Some Author',
            'genre' => 'Fiction',
            'synopsis' => 'Test book description.',
            'available_copies' => -1, // Invalid value
        ]);

        $response->assertSessionHasErrors('available_copies');
    }
}
