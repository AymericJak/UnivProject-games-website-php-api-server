<?php

namespace Database\Seeders;

use App\Models\Achat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            for ($j = $i; $j <= 5; $j++) {
                Achat::factory()->create([
                    "user_id" => $i,
                    "jeu_id" => $j,
                ]);
            }
        }
    }
}
