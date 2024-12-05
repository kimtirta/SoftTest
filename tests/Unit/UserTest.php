<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /** @test */
    public function it_should_require_name()
    {
        $user = new User();
        $validator = Validator::make($user->toArray(), $user->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The name field is required.', $validator->errors()->all());
    }

    /** @test */
    public function it_should_require_email()
    {
        $user = new User();
        $validator = Validator::make($user->toArray(), $user->rules());

        $this->assertTrue($validator->fails());
        $this->assertContains('The email field is required.', $validator->errors()->all());
    }

    /** @test */
    public function it_should_have_unique_email()
    {
        User::create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => bcrypt('password123')
        ]);

        $user = new User([
            'name' => 'John Doe',
            'email' => 'janedoe@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->assertFalse($user->save());  // Fails because the email is not unique
    }

    /** @test */
    public function it_should_belong_to_many_books_via_loans()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'due_date' => now()->addDays(14),
        ]);

        $this->assertTrue($user->books->contains($book)); // Ensure the book is in the user's books collection
    }

    /** @test */
    public function it_should_return_error_for_invalid_email_format()
    {
        $user = new User([
            'name' => 'Invalid User',
            'email' => 'invalidemail',
            'password' => bcrypt('password123')
        ]);

        $this->assertFalse($user->save());  // Invalid email format
    }

    /** @test */
    public function it_should_return_true_if_user_is_admin()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword123'),
            'role' => 'admin'
        ]);

        $this->assertTrue($admin->isAdmin());  // Check if the user is admin based on role
    }

    /** @test */
    public function it_should_return_false_if_user_is_not_admin()
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('userpassword123'),
            'role' => 'user'
        ]);

        $this->assertFalse($user->isAdmin());  // Check if the user is not admin based on role
    }

    /** @test */
    public function it_should_handle_password_encryption()
    {
        $user = User::create([
            'name' => 'Encrypted User',
            'email' => 'encrypted@example.com',
            'password' => 'plainpassword'
        ]);

        $this->assertNotEquals('plainpassword', $user->password);  // Password should be hashed
        $this->assertTrue(password_verify('plainpassword', $user->password));  // Verify password
    }
}

