<?php

namespace Database\Seeders;

use App\Models\Jeu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JeuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jeu::factory(10)->create();
    }
}
