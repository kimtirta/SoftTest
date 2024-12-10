<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    public function run()
    {
        // Get all users and books
        $users = User::all();
        $books = Book::all();

        // Create 50 dummy loan records
        foreach ($users as $user) {
            foreach ($books->random(2) as $book) { // Each user gets 2 random books for the loan
                Loan::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'due_date' => Carbon::now()->addDays(rand(7, 30)), // Random due date within 7 to 30 days
                    'returned_date' => null, // Keep it null for active loans
                ]);
            }
        }

        // You can also generate some past loans by adding a few records with returned_date
        Loan::factory(10)->create([
            'returned_date' => Carbon::now()->subDays(rand(1, 30)) // Add random past loans
        ]);
    }
}
