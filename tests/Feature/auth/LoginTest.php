<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('login|test@example.com|127.0.0.1');
    }

    public function test_user_can_login_without_mfa_and_redirects_to_correct_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => UserRoles::CLIENT,
            'mfa_enabled' => false,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('client.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_with_mfa_is_redirected_to_mfa_screen()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => UserRoles::CLIENT,
            'mfa_enabled' => true,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('mfa.show'));
        
        $this->assertEquals($user->id, session('mfa_user_id'));
        $this->assertFalse(session('mfa_remember'));
    }

    public function test_logged_in_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertFalse(auth()->check());
    }
    
    public function test_suspended_user_cannot_login()
    {
        User::factory()->create([
            'email' => 'suspended@test.com',
            'password' => bcrypt('password123'),
            'status' => 'suspended',
        ]);

        $response = $this->post('/login', [
            'email' => 'suspended@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        
        $this->assertFalse(session()->has('mfa_user_id'));
    }

    public function test_user_is_rate_limited_after_5_failed_attempts()
    {
        User::factory()->create(['email' => 'test@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
    }

    public function test_zombie_account_is_deleted_on_login_attempt()
    {
        // Create an unverified user with old created_at
        $zombie = User::factory()->create([
            'email' => 'zombie@test.com',
            'email_verified_at' => null,
            'created_at' => now()->subMinutes(11),
        ]);

        $this->post('/login', [
            'email' => 'zombie@test.com',
            'password' => 'password123',
        ]);

        $this->assertDatabaseMissing('users', ['email' => 'zombie@test.com']);
    }
}