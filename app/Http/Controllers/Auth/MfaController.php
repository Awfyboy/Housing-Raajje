<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class MfaController extends Controller
{
    public function show(Request $request)
    {
        if (! $request->session()->has('mfa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.mfa');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'digits:6']]);

        $userId = $request->session()->get('mfa_user_id');
        $user   = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        //Rate limit MFA attempts per user
        $key = 'mfa|' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'code' => "Too many attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // Look up the latest unused, unexpired login token
        $token = $user->mfaTokens()
            ->where('purpose', 'login')
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $token || ! Hash::check($request->code, $token->code)) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'code' => 'The code is invalid or has expired.',
            ]);
        }

        // Mark the token as used so it can't be replayed
        $token->update(['used_at' => now()]);

        RateLimiter::clear($key);

        Auth::login($user);
        $request->session()->forget('mfa_user_id');
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}