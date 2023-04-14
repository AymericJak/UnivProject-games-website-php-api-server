<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Role::factory()->create([
            "nom" => "administrateur"
        ]);

        Role::factory()->create([
            "nom" => "commentaire-moderateur"
        ]);

        Role::factory()->create([
            "nom" => "adherent-premium"
        ]);

        Role::factory()->create([
            "nom" => "adherent"
        ]);


        Role::factory()->create([
            "nom" => "visiteur"
        ]);
    }
}
