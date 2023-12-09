<?php

namespace Database\Factories;

use App\Models\RolUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'name_empresa' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'start_at' => now(),
            'end_at' => now(),
            'profile' => "https://project-jawas.s3.eu-west-3.amazonaws.com/perfiles/QpjAQcQk1VjSEsu4QefNOWnZvZRShaU5zzNnX1YV.jpg"
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure(): static
    {
        return
        $this
        ->afterCreating(function ($user) {
            $numRoles = rand(0,4);
            RolUser::factory($numRoles)->create(['user_id' => $user->id, 'rol_id'=>1]);
        })
        ->afterMaking(function ($user) {
           $user->save();
        })        ;
    }
}
