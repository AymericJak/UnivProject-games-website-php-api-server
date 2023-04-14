<?php

namespace Database\Factories;

use App\Models\Jeu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commentaire>
 */
class CommentaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $debut = $this->faker->dateTimeBetween('-3 months', '+3months');
        return [
            'jeu_id'=>$this->faker->randomElement(Jeu::all()->pluck('id')),
            'user_id'=>$this->faker->randomElement(User::all()->pluck('id')),
            'commentaire' => substr($this->faker->paragraph(),0, 100),
            'date_com' => $this->faker->dateTimeBetween($debut, $this->faker->dateTimeInInterval($debut, '3 hours')),
            'note' => $this->faker->numberBetween(1, 5),
            'etat' => $this->faker->randomElement(['attente de validation', 'public']),

        ];
    }
}
