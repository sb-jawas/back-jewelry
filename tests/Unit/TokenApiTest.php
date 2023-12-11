<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

/**
 * @author: badr => @bhamidou
 */

class TokenApiTest extends TestCase
{
    /**
     * Registro de un usuario.
     */
    public function test_signup(): void
    {
        $userData = [
            'name' => 'pruebaUser',
            'profile' => 'https://project-jawas.s3.eu-west-3.amazonaws.com/perfiles/QpjAQcQk1VjSEsu4QefNOWnZvZRShaU5zzNnX1YV.jpg',
            'name_empresa' => 'repsol',
            'email' => 'user@user.com',
            'password' => bcrypt('P@ssword123'),
            'start_at' => now(),
        ];

        $response = $this->post('/register', $userData);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }
}
