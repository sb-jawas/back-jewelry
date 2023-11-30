<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\StatusCode::factory()->create([
            'name' => 'Creado',
            'desc' => 'Se acaba de crear un lote, muy pronto se pasará Chubaca a por el!',
        ]);

        \App\Models\StatusCode::factory()->create([
            'name' => 'Recogido',
            'desc' => 'En este momento se encuentra en camino al almacen',
        ]);

        \App\Models\StatusCode::factory()->create([
            'name' => 'Entregado',
            'desc' => 'En este momento se encuentra en el almacen',
        ]);

        \App\Models\StatusCode::factory()->create([
            'name' => 'Clasificando',
            'desc' => 'Nuestros jawas están despiezando este lote',
        ]);

        \App\Models\StatusCode::factory()->create([
            'name' => 'Clasificado',
            'desc' => 'Este lote ya se ha clasificado correctamente',
        ]);

        \App\Models\StatusCode::factory()->create([
            'name' => 'Rechazado',
            'desc' => 'Este lote no contiene lo necesario para poder crear una joya',
        ]);
    }
}
