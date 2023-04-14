<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achat>
 */
class AchatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 5),
            'jeu_id' => $this->faker->numberBetween(1, 5),
            'date_achat' => $this->faker->dateTime(now()),
            'lieu_achat' => $this->faker->city(),
            'prix' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
