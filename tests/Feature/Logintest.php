<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected $loginRules;
    protected $loginMessages;

    public function setUp(): void
    {
        parent::setUp();

        // Defining validation rules and error messages for login
        $this->loginRules = [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:60',
        ];

        $this->loginMessages = [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus dalam format yang benar.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 60 karakter.',
        ];
    }

    /**
     * Test user login functionality.
     *
     * @dataProvider loginDataProvider
     */
    public function test_user_login_with_various_credentials($email, $password, $isValid, $expectedMessages = [])
    {
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        // Validate input before making the request
        $validator = Validator::make($credentials, $this->loginRules, $this->loginMessages);
        $this->assertEquals(!$validator->fails(), $isValid);

        if (!$isValid) {
            $errorMessages = $validator->errors()->all();
            foreach ($expectedMessages as $expectedMessage) {
                $this->assertContains(
                    $expectedMessage,
                    $errorMessages,
                    "Failed asserting that the message contains the expected message: '$expectedMessage'."
                );
            }
        }

        if ($isValid) {
            $response = $this->post(route('user.login.submit'), $credentials);
            $response->assertRedirect(route('user.dashboard'));
            $this->assertAuthenticatedAs($user);
        } else {
            $response = $this->post(route('user.login.submit'), $credentials);
            $response->assertSessionHasErrors();
            $this->assertGuest();
        }
    }

    /**
     * Data provider for login testing.
     *
     * @return array
     */
    public static function loginDataProvider()
    {
        return [
            // Valid email and password
            ['validemail@example.com', 'ValidPassword123', true],
            
            // Invalid email formats (Partition Testing - Invalid Email)
            ['invalid-email.com', 'ValidPassword123', false, ['Email harus dalam format yang benar.']],
            ['', 'ValidPassword123', false, ['Email harus diisi.']],
            ['@example.com', 'ValidPassword123', false, ['Email harus dalam format yang benar.']],

            // Valid and invalid passwords (Boundary Testing for Password Length)
            ['validemail@example.com', 'aaa', false, ['Password minimal 8 karakter.']],  // Invalid password - too short
            ['validemail@example.com', str_repeat('a', 256), false, ['Password maksimal 60 karakter.']],  // Invalid password - too long
            ['validemail@example.com', '', false, ['Password harus diisi.']],  // Missing password
            
            // Boundary testing for password length (Boundary Testing)
            ['validemail@example.com', 'aaaaa', false, ['Password minimal 8 karakter.']], // Just below valid password length
            ['validemail@example.com', str_repeat('a', 60), true],  // Exactly the maximum allowed password length
            ['validemail@example.com', str_repeat('a', 61), false, ['Password maksimal 60 karakter.']],  // Just above valid password length

            // Testing with email close to max length (Boundary Testing for Email Length)
            [str_repeat('a', 244) . '@domain.com', 'ValidPassword123', true],  // Max length email (255 characters including @domain.com)
            ['a' . str_repeat('a', 244) . '@domain.com', 'ValidPassword123', false, ['Email tidak boleh lebih dari 255 karakter.']],  // Invalid email, 256 characters

            // Boundary testing for empty inputs
            ['', '', false, ['Email harus diisi.', 'Password harus diisi.']],  // Both fields empty

            // Testing with valid but non-existent credentials (Boundary Testing for Authentication)
            ['nonexistent@example.com', 'ValidPassword123', false, ['These credentials do not match our records.']],  // Non-existent user

            // Testing with email that doesn't match registered password (Partition Testing - Invalid Credentials)
            ['validemail@example.com', 'WrongPassword123', false, ['These credentials do not match our records.']],  // Incorrect password
        ];
    }

    /**
     * Test admin login functionality.
     *
     * @dataProvider adminLoginDataProvider
     */
    public function test_admin_login_with_various_credentials($email, $password, $isValid, $expectedMessages = [])
    {
        $admin = Admin::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        // Validate input before making the request
        $validator = Validator::make($credentials, $this->loginRules, $this->loginMessages);
        $this->assertEquals(!$validator->fails(), $isValid);

        if (!$isValid) {
            $errorMessages = $validator->errors()->all();
            foreach ($expectedMessages as $expectedMessage) {
                $this->assertContains(
                    $expectedMessage,
                    $errorMessages,
                    "Failed asserting that the message contains the expected message: '$expectedMessage'."
                );
            }
        }

        if ($isValid) {
            $response = $this->post(route('admin.login.submit'), $credentials);
            $response->assertRedirect(route('admin.dashboard'));
            $this->assertAuthenticatedAs($admin, 'admin');
        } else {
            $response = $this->post(route('admin.login.submit'), $credentials);
            $response->assertSessionHasErrors();
            $this->assertGuest();
        }
    }

    /**
     * Data provider for admin login testing.
     *
     * @return array
     */
    public static function adminLoginDataProvider()
    {
        return [
            ['admin@domain.com', 'password', true],
            ['admin@domain.com', '', false, ['Password harus diisi.']],
            ['admin@domain.com', 'short', false, ['Password minimal 8 karakter.']],
            ['invalid@domain.com', 'password', false, ['These credentials do not match our records.']],
        ];
    }
}
