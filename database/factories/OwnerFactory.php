<?php

namespace Database\Factories;

use App\Models\Entity\Owner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class OwnerFactory extends Factory
{
    protected $model = Owner::class;

    public function definition(): array
    {
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'role' => \App\Enums\UserRoles::PROPERTY_OWNER,
            'status' => 'active',
            'email_verified_at' => now(),
            'phone' => '7' . $this->faker->numerify('#######'),
        ]);

        return [
            'user_id' => $user->id,
            'national_id' => $this->faker->numerify('A#######'),
            'bank_account' => $this->faker->numerify('BML-#########'),
            'verified_phone' => true,
            'address' => $this->faker->address(),
        ];
    }
}