<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserConsistencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_two_users_with_same_email_should_fail()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        $this->post(route('users.store'), $userData);
        $response = $this->post(route('users.store'), $userData);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function updating_user_data_should_be_consistent()
    {
        $user = User::factory()->create();

        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
        ];

        $this->put(route('users.update', $user->id), $updatedData);

        $this->assertDatabaseHas('users', $updatedData);
    }
}
