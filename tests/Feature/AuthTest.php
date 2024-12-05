<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_books_page()
    {
        $response = $this->get(route('books.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_access_books_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('books.index'));

        $response->assertStatus(200);
    }
}
