<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRoles; 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entity\AuditLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        User::whereNull('email_verified_at') 
            ->where('created_at', '<', now()->subMinutes(10))
            ->delete();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:client,property_owner'],
        ]);

        $role = $data['role'] === 'property_owner' 
            ? UserRoles::PROPERTY_OWNER 
            : UserRoles::CLIENT;

        //External users (Client, Owner) = false
        //Internal users (Admin, Manager, Staff, Receptionist) = true
        $isExternalUser = in_array($role, [
            UserRoles::CLIENT, 
            UserRoles::PROPERTY_OWNER
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],   
            'role'     => $role,
            'mfa_enabled' => !$isExternalUser //true for staff, false for external
        ]);

        event(new Registered($user));
        
        Auth::login($user);
        $request->session()->regenerate();

        //Audit Log:Public Registration
        AuditLog::create([
            'actor_id' => $user->id,
            'action' => 'REGISTER',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "{$user->name} registered as a {$user->role->label()}.",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('verification.notice');
    }

    public function resendVerification(Request $request)
    {
        $key = 'resend-verification|' . $request->user()->id;
        
        if (RateLimiter::tooManyAttempts($key, 2)) {
            $seconds = RateLimiter::availableIn($key);
            
            return back()->withErrors([
                'resend' => "Please wait {$seconds} seconds before requesting another email."
            ]);
        }

        RateLimiter::hit($key, 60);
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'A fresh verification link has been sent to your email.');
    }
}