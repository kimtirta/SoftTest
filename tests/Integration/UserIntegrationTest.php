<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_signup_with_valid_data()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/signup', $data);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'User registered successfully.']);
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }

    /** @test */
    public function user_cannot_signup_with_invalid_data()
    {
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ];

        $response = $this->postJson('/signup', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Login successful.']);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid credentials.']);
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logout successful.']);
    }

    /** @test */
    public function guest_cannot_access_protected_routes()
    {
        $response = $this->getJson('/profile');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function authenticated_user_can_access_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/profile');

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);
    }

    /** @test */
    public function user_can_update_profile_with_valid_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson('/profile', $data);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Profile updated successfully.']);
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }

    /** @test */
    public function user_cannot_update_profile_with_invalid_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $data = [
            'name' => '',
            'email' => 'invalid-email',
        ];

        $response = $this->putJson('/profile', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    /** @test */
    public function user_can_delete_their_account()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson('/profile');

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function user_cannot_delete_other_users_account()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson("/users/{$otherUser->id}");

        $response->assertStatus(403);
        $response->assertJson(['message' => 'Forbidden.']);
    }
}