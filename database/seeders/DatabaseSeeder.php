<?php

use App\Models\Book;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Admin
        Admin::create([
            'name' => 'Admin Library',
            'email' => 'admin@library.com',
            'password' => Hash::make('password'),
        ]);

        // Users
        User::factory(10)->create();

        // Books
        Book::factory(50)->create();

        \App\Models\Loan::factory(50)->create();

    }
}

