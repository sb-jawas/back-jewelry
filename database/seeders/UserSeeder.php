<?php

namespace Database\Seeders;

use App\Models\RolUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         \App\Models\User::factory(10)->create();
         \App\Models\User::factory()->create([
            'name' => 'Fernanado',
            'name_empresa' => 'Virgen de gracia',
            'email' => 'fernando@cifp.com',
            'password' => "Chubaca2024",
            'start_at' => now(),
            'end_at' => now(),
        ]);
    }

    
}
