<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new user with valid data.
     */
    public function test_create_user_with_valid_data()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /**
     * Test reading user data.
     */
    public function test_read_user_data()
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
        ]);

        $retrievedUser = User::find($user->id);

        $this->assertEquals('Jane Doe', $retrievedUser->name);
        $this->assertEquals('janedoe@example.com', $retrievedUser->email);
    }

    /**
     * Test updating user data.
     */
    public function test_update_user_data()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'oldname@example.com',
        ]);

        $user->update([
            'name' => 'New Name',
            'email' => 'newname@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'New Name',
            'email' => 'newname@example.com',
        ]);
    }

    /**
     * Test deleting a user.
     */
    public function test_delete_user()
    {
        $user = User::factory()->create([
            'name' => 'Deletable User',
            'email' => 'deleteuser@example.com',
        ]);

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'email' => 'deleteuser@example.com',
        ]);
    }

    /**
     * Test creating a user with missing required fields.
     */
    public function test_create_user_with_missing_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'name' => null,
            'email' => null,
        ]);
    }

    /**
     * Test creating a user with duplicate email.
     */
    public function test_create_user_with_duplicate_email()
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);
    }

    /**
     * Test creating a user with invalid email format.
     */
    public function test_create_user_with_invalid_email_format()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'email' => 'invalid-email',
        ]);
    }

    /**
     * Test creating a user with short password.
     */
    public function test_create_user_with_short_password()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'password' => bcrypt('short'),
        ]);
    }

    /**
     * Test relationship with loans (if exists).
     */
    public function test_user_has_loans_relationship()
    {
        $user = User::factory()->create();

        $loan = $user->loans()->create([
            'amount' => 5000,
            'status' => 'pending',
        ]);

        $this->assertEquals(1, $user->loans()->count());
        $this->assertTrue($user->loans->contains($loan));
    }
}
