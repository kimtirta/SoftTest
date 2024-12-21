<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_admin_login_with_valid_credentials()
    {
        $admin = Admin::create([
            'name' => 'Admin Library',
            'email' => 'admin@library.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@library.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_login_with_invalid_credentials()
{
    $response = $this->post(route('admin.login.submit'), [
        'email' => 'invalid@library.com',
        'password' => 'wrongPassword',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest('admin');
}


    public function test_user_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@domain.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('user.login.submit'), [
            'email' => 'user@domain.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_login_with_invalid_credentials()
    {
        $response = $this->post('/user/login', [
            'email' => 'wrong@domain.com',
            'password' => 'wrongPassword',
        ]);
    
        $response->assertSessionHasErrors(['email']); // Expecting email error
        $this->assertGuest(); // Ensuring the user is not logged in
    }
    

    public function test_admin_login_with_missing_fields()
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => '',
            'password' => '',
        ]);
    
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('admin');
    }
    

    public function test_user_login_with_missing_fields()
{
    $response = $this->post('/user/login', [
        'email' => '',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['email', 'password']); // Expecting both email and password errors
    $this->assertGuest(); // Ensuring the user is not logged in
}
}
