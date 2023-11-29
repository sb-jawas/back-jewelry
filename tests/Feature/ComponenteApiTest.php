<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComponenteApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_ComponenteApi()
    {
        $response = $this->get('/api/componentes');

        $response->assertStatus(200)
                 ->assertExactJson([
                     "id" => 1,
                     "name" => "CPU",
                     "desc" => "Unidad Central de Procesamiento",
                     "is_hardware" => 1,
                     "created_user_id" => 1
                 ]);
    }
}
