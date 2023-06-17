<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            for ($j = $i; $j <= 5; $j++) {
                RoleUser::factory()->create([
                    'user_id' => $i,
                    'role_id' => $j,
                ]);
            }
        }
    }
}
