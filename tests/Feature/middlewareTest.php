<?php

namespace Tests\Feature;

use App\Models\RolUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class middlewareTest extends TestCase
{
    /**
     * Prueba para comprobar las abilities
     */
    public function test_abilities(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'P@ssword123',
        ]);

        $response->assertStatus(200);

        $token = $response->json('token');

        $testingAbility = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->get('/api/admin/');

        $testingAbility->assertStatus(401);


    }
}
