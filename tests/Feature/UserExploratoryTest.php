<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserExploratoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_user_should_handle_special_characters_in_name()
    {
        $response = $this->post(route('users.store'), [
            'name' => '@!#%John$%',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => '@!#%John$%']);
    }

    /** @test */
    public function creating_user_with_missing_email_should_fail()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
