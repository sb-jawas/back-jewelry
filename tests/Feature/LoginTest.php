<?php

namespace Tests\Feature;

use App\Models\RolUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_login(): void
    {
        $user = User::create([
            'name' => 'test',
            'name_empresa' => 'test',
            'email' => 'test@example.com',
            'start_at' => now(),
            'password' => bcrypt('P@ssword123'),
        ]);

        // RolUser::destroy($user->id);
        $rolUser = [
            "user_id"=>$user->id,
             "rol_id" =>2
            ];

        RolUser::create($rolUser);
        $response = $this->post('api/login', [
            'email' => 'test@example.com',
            'password' => 'P@ssword123',
        ]);
        
        $this->assertAuthenticatedAs($user);
    }
}
