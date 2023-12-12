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
         \App\Models\User::create([
            'name' => 'Fernando',
            'name_empresa' => 'Virgen de gracia',
            'email' => 'fernando@cifp.com',
            'password' => "Chubaca2024",
            'start_at' => now(),
            'end_at' => now(),
            'profile' => "https://project-jawas.s3.eu-west-3.amazonaws.com/perfiles/QpjAQcQk1VjSEsu4QefNOWnZvZRShaU5zzNnX1YV.jpg"
        ]);
    }

    
}
