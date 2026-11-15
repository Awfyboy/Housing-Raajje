<?php

namespace Database\Factories;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRoles::CLIENT,
            'mfa_enabled' => false,
            'status' => 'active',
        ];
    }

    // email unverified
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // mfa enabled
    public function mfaEnabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'mfa_enabled' => true,
        ]);
    }

    // admin role
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoles::ADMIN,
        ]);
    }

    // staff role
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoles::STAFF,
        ]);
    }

    // branch manager role
    public function branchManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoles::BRANCH_MANAGER,
        ]);
    }

    // receptionist role
    public function receptionist(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoles::RECEPTIONIST,
        ]);
    }

    // property owner role
    public function propertyOwner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoles::PROPERTY_OWNER,
        ]);
    }
}