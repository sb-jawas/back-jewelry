<?php

namespace Tests\Feature;

use App\Models\RolUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComponenteApiTest extends TestCase
{
    /**
     * A Testing componentes.
     */
    public function test_ComponenteApi()
    {
        $user = User::factory()->create();
        $rolUser = [
            "user_id"=>$user->id,
             "rol_id" =>2
            ];
        RolUser::create($rolUser);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'P@ssword123',
        ]);

        $response->assertStatus(200);

        $token = $response->json('token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->get('/api/componentes/1');

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
