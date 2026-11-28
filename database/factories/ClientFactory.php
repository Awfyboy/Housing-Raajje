<?php

namespace Database\Factories;

use App\Models\Entity\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ClientFactory extends Factory
{
    protected $model = \App\Models\Entity\Client::class;

    public function definition(): array
    {
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'role' => \App\Enums\UserRoles::CLIENT,
            'status' => 'active',
            'email_verified_at' => now(),
            'phone' => '7' . $this->faker->numerify('#######'),
        ]);

        return [
            'user_id' => $user->id,
            'full_name' => $user->name,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->dateTimeBetween('-40 years', '-20 years')->format('Y-m-d'),
            'phone' => $user->phone,
            'maximum_rent' => $this->faker->numberBetween(10000, 30000),
            'preferred_location' => $this->faker->randomElement(['Malé', 'Hulhumalé', 'Villingili']),
            'preferred_rooms' => $this->faker->numberBetween(1, 4),
            'preferred_lease' => $this->faker->randomElement([6, 12, 24, 36]),
        ];
    }
}