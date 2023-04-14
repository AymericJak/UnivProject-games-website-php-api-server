<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        User::factory()->create([
            'email' => 'administrateur@test.fr',
        ]);

        User::factory()->create([
            'email' => 'commentaire-moderateur@test.fr',
        ]);

        User::factory()->create([
            'email' => 'adherent-premium@test.fr',
        ]);

        User::factory()->create([
            'email' => 'adherent@test.fr',
        ]);

        User::factory()->create([
            'email' => 'visiteur@test.fr',
        ]);
    }
}
