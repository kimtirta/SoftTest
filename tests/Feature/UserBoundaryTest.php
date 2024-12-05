<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBoundaryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_email_cannot_be_empty()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_name_max_length_is_255_characters()
    {
        $response = $this->post(route('users.store'), [
            'name' => str_repeat('A', 256), // One more than max length
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function user_password_minimum_length_is_8_characters()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'short', // Invalid password
        ]);

        $response->assertSessionHasErrors('password');
    }
}
