<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Database\Factories\CategorieFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categorie::factory(40)->create();
    }
}
