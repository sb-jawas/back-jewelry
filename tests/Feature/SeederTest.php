<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SeederTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_seeder(): void
    {
        Artisan::call('db:seed', ['--class' => 'RolSeeder']);
        $this->assertDatabaseCount('rol', 4);
    }
}
