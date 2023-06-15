<?php

namespace Database\Factories;

use App\Models\Categorie;
use App\Models\Editeur;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class JeuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorie = Categorie::pluck('id')->toArray();
        $theme = Theme::pluck('id')->toArray();
        $editeur = Editeur::pluck('id')->toArray();

        return [
            'nom' => fake()->unique()->word(),
            'description' => fake()->unique()->paragraph(),
            'langue' => fake()->languageCode(),
            'url_media' => 'no-image.png',
            'age_min' => fake()->randomElement([8,12,16,18]),
            'nombre_joueurs_min' => fake()->numberBetween(1,20),
            'nombre_joueurs_max' => fake()->numberBetween(1,20),
            'duree_partie' => fake()->numberBetween(100,200),
            'valide' => true,
            'categorie_id' => $this->faker->randomElement($categorie),
            'theme_id' => $this->faker->randomElement($theme),
            'editeur_id' => $this->faker->randomElement($editeur),
        ];
    }
}
