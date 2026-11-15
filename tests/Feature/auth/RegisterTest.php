<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_client()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@test.com',
            'role' => 'client',
            'mfa_enabled' => false,
            'email_verified_at' => null,
        ]);

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_user_can_register_as_property_owner()
    {
        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'property_owner',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@test.com',
            'role' => 'property_owner',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_invalid_role_is_rejected()
    {
        $response = $this->post('/register', [
            'name' => 'Bad Actor',
            'email' => 'bad@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Not allowed publicly
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'bad@test.com']);
    }
}