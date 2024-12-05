<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookEquivalenceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_book_with_valid_data_should_pass()
    {
        $response = $this->post(route('books.store'), [
            'title' => 'Valid Book Title',
            'author' => 'Valid Author',
            'genre' => 'Fiction',
            'synopsis' => 'A valid book synopsis.',
            'available_copies' => 5,
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['title' => 'Valid Book Title']);
    }

    /** @test */
    public function creating_a_book_without_required_fields_should_fail()
    {
        $response = $this->post(route('books.store'), [
            'title' => '',
            'author' => '',
            'genre' => '',
            'synopsis' => '',
            'available_copies' => 0,
        ]);

        $response->assertSessionHasErrors(['title', 'author', 'genre']);
    }
}
