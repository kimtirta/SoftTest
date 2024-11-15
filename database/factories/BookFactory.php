<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'author' => $this->faker->name(),
            'genre' => $this->faker->word(),
            'synopsis' => $this->faker->paragraph(),
            'available_copies' => $this->faker->numberBetween(1, 10),
        ];
    }
}
