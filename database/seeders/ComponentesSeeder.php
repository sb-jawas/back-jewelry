<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComponentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Componentes::factory()->create([
            "name" => "CPU",
            "desc" => "Unidad Central de Procesamiento",
            "is_hardware"=>1,
            "created_user_id" => 1
        ]);
        
        \App\Models\Componentes::factory()->create([
            "name" => "RAM",
            "desc" => "Memoria de Acceso Aleatorio",
            "is_hardware"=>1,
            "created_user_id" => 1
        ]);
        
        \App\Models\Componentes::factory()->create([
            "name" => "GPU",
            "desc" => "Unidad de Procesamiento de Gráficos",
            "is_hardware"=>1,
            "created_user_id" => 1
        ]);
        
        \App\Models\Componentes::factory()->create([
            "name" => "HDD",
            "desc" => "Disco Duro",
            "is_hardware"=>1,
            "created_user_id" => 1
        ]);
        
        \App\Models\Componentes::factory()->create([
            "name" => "SSD",
            "desc" => "Unidad de Estado Sólido",
            "is_hardware"=>1,
            "created_user_id" => 1
        ]);
        
    }
}
