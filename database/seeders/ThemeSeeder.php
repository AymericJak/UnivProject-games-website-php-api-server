<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Theme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Theme::factory(40)->create();
    }
}
