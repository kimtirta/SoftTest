<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'User registered successfully',
                     'data' => [
                         'name' => $data['name'],
                         'email' => $data['email'],
                     ],
                 ]);
    }

    /** @test */
    public function user_cannot_register_with_missing_fields()
    {
        $data = [
            'name' => 'John Doe',
            // Missing email
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422) // Validation failed
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_cannot_register_with_existing_email()
    {
        $existingUser = User::factory()->create(['email' => 'johndoe@example.com']);

        $data = [
            'name' => 'John Doe',
            'email' => $existingUser->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(409) // Conflict
                 ->assertJson([
                     'message' => 'Email already exists.',
                 ]);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401) // Unauthorized
                 ->assertJson([
                     'message' => 'Invalid credentials.',
                 ]);
    }

    /** @test */
    public function user_cannot_login_with_invalid_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalidemail@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401) // Unauthorized
                 ->assertJson([
                     'message' => 'Invalid credentials.',
                 ]);
    }
}