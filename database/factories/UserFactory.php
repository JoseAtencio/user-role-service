<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'role_id' => Role::factory(), // Genera un role_id usando la fábrica de Role
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'dni' => $this->faker->unique()->numerify('#########'),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
        ];
    }
}