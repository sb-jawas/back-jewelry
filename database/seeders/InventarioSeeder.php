<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Inventario::create([
            "cantidad" => 0,
            "componente_id" => 1
        ]);
        
        \App\Models\Inventario::create([
            "cantidad" => 0,
            "componente_id" => 2
        ]);
        
        \App\Models\Inventario::create([
            "cantidad" => 0,
            "componente_id" => 3
        ]);
        
        \App\Models\Inventario::create([
            "cantidad" => 0,
            "componente_id" => 4
        ]);
        
        \App\Models\Inventario::create([
            "cantidad" => 0,
            
            "componente_id" => 5
        ]);
    }
}
