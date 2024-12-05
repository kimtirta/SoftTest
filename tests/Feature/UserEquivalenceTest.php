<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserEquivalenceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_user_with_valid_data_should_pass()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
    }

    /** @test */
    public function creating_a_user_with_empty_name_should_fail()
    {
        $response = $this->post(route('users.store'), [
            'name' => '',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
