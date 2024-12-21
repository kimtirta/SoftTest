<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'returned_date' => null, // Default value for new loans
        ];
    }
}
