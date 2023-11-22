<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\Rol::factory()->create([
            'name' => 'Colaborador',
            'desc' => 'Usuario con capacidad de donar',
        ]);
        
        \App\Models\Rol::factory()->create([
            'name' => 'Clasificador',
            'desc' => 'Usuario con capacidad de gestionar lotes',
        ]);

        \App\Models\Rol::factory()->create([
            'name' => 'Diseñador',
            'desc' => 'Usuario con capacidad de crear joyas',
        ]);

        \App\Models\Rol::factory()->create([
            'name' => 'Admin',
            'desc' => 'Usuario con capacidad de gestionar de usuarios',
        ]);

    }
}
