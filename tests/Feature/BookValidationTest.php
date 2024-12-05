<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function validation_fails_when_creating_book_without_required_fields()
    {
        $response = $this->post(route('books.store'), []);

        $response->assertSessionHasErrors(['title', 'author']);
    }

    /** @test */
    public function validation_fails_when_creating_book_with_invalid_data()
    {
        $response = $this->post(route('books.store'), [
            'title' => 'The Great Gatsby',
            'author' => '',
            'genre' => '',
            'synopsis' => '',
        ]);

        $response->assertSessionHasErrors(['author', 'genre']);
    }
}
