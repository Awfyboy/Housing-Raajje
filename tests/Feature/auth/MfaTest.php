<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class MfaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('mfa|1');
    }

    public function test_valid_mfa_code_logs_user_in()
    {
        $user = User::factory()->create([
            'role' => UserRoles::CLIENT,
            'email_verified_at' => now(),
        ]);

        $code = '123456';
        $user->mfaTokens()->create([
            'code' => Hash::make($code),
            'purpose' => 'login',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->withSession(['mfa_user_id' => $user->id])
            ->post('/mfa', ['code' => $code]);

        $response->assertRedirect(route('client.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_mfa_code_fails()
    {
        $user = User::factory()->create();
        $user->mfaTokens()->create([
            'code' => Hash::make('123456'),
            'purpose' => 'login',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->withSession(['mfa_user_id' => $user->id])
            ->post('/mfa', ['code' => '000000']);

        $response->assertSessionHasErrors('code');
        $this->assertGuest();
    }
}