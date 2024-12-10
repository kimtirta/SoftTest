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
            'user_id' => User::inRandomOrder()->first()->id,
            'book_id' => Book::inRandomOrder()->first()->id,
            'due_date' => Carbon::now()->addDays(rand(7, 30)),
            'returned_date' => null, // You can randomly make it returned too
        ];
    }
}
