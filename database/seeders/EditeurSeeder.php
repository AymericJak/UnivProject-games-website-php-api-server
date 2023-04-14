<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Editeur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EditeurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Editeur::factory(40)->create();
    }
}
