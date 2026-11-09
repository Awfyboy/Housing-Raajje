<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Entity\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // 1. Validate basic fields
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        // 2. Update base user
        $user->update($data);

        // 3. Handle role-specific fields
        if ($user->role === UserRoles::CLIENT) {
            // 🆕 Validate Client fields
            $clientData = $request->validate([
                'gender' => ['nullable', 'string', 'in:male,female,other'],
                'date_of_birth' => ['nullable', 'date'],
                'maximum_rent' => ['nullable', 'numeric', 'min:0'],
                'preferred_location' => ['nullable', 'string', 'max:255'],
                'preferred_rooms' => ['nullable', 'integer', 'min:1'],
                'preferred_lease' => ['nullable', 'integer', 'min:1'],
            ]);

            $user->clientProfile()->updateOrCreate(
                ['user_id' => $user->id],
                array_merge($clientData, [
                    'full_name' => $user->name,
                    'phone' => $request->phone,
                ])
            );
        } elseif ($user->role === UserRoles::PROPERTY_OWNER) {
            // 🆕 Validate Owner fields
            $ownerData = $request->validate([
                'national_id' => ['nullable', 'string', 'max:255'],
                'bank_account' => ['nullable', 'string', 'max:255'],
                'address' => ['nullable', 'string'],
                'verified_phone' => ['nullable', 'boolean'],
            ]);

            $user->ownerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $ownerData
            );
        }

        //Audit Log: Profile Updated
        AuditLog::create([
            'actor_id' => $user->id,
            'action' => 'UPDATE_PROFILE',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "{$user->name} updated their profile details.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', 'Profile updated successfully!');
    }


    //Update Password (Instant for Client/Owner, Admin Request for Staff)
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        //Staff roles require admin approval
        if (in_array($user->role, [
            UserRoles::BRANCH_MANAGER,
            UserRoles::STAFF,
            UserRoles::RECEPTIONIST,
        ])) {
            // 🆕 Check for existing pending request (prevents overwriting)
            if ($user->admin_request_status === 'pending') {
                return back()->withErrors([
                    'password' => 'You already have a pending request. Please wait for the admin to review it before submitting another.'
                ]);
            }

            $user->update([
                'admin_request_type' => 'password_change',
                'admin_request_note' => Hash::make($request->password), // Store hashed new password temporarily
                'admin_request_status' => 'pending',
            ]);

            //Audit Log: Password Change Requested
            AuditLog::create([
                'actor_id' => $user->id,
                'action' => 'REQUEST_PASSWORD_CHANGE',
                'entity_type' => 'User',
                'entity_id' => $user->id,
                'description' => "{$user->name} requested a password change (pending admin approval).",
                'ip_address' => request()->ip(),
            ]);

            return back()->with('admin_request_sent', 'password');
        }

        //Client/Owner: update immediately
        $user->update(['password' => Hash::make($request->password)]);

        //Audit Log: Password Changed
        AuditLog::create([
            'actor_id' => $user->id,
            'action' => 'CHANGE_PASSWORD',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "{$user->name} changed their password.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', 'Password updated successfully!');
    }


    //Toggle MFA on/off
    public function toggleMfa(Request $request)
    {
        $user = $request->user();
        $newState = !$user->mfa_enabled;
        $user->update(['mfa_enabled' => $newState]);

        //Audit Log: MFA Toggled
        AuditLog::create([
            'actor_id' => $user->id,
            'action' => $newState ? 'ENABLE_MFA' : 'DISABLE_MFA',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "{$user->name} " . ($newState ? 'enabled' : 'disabled') . " Multi-Factor Authentication.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', 'MFA setting updated successfully!');
    }


    //Update Email (Verification Link for Client/Owner, Admin Request for Staff)
    public function updateEmail(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'new_email' => [
                'required', 
                'email', 
                'max:255', 
                Rule::unique('users', 'email')->ignore($user->id)
            ],
        ]);

        //Staff roles require admin approval
        if (in_array($user->role, [
            UserRoles::ADMIN,
            UserRoles::BRANCH_MANAGER,
            UserRoles::STAFF,
            UserRoles::RECEPTIONIST,
        ])) {
            //Check for existing pending request (prevents overwriting)
            if ($user->admin_request_status === 'pending') {
                return back()->withErrors([
                    'new_email' => 'You already have a pending request. Please wait for the admin to review it before submitting another.'
                ]);
            }

            $user->update([
                'admin_request_type' => 'email_change',
                'admin_request_note' => $request->new_email,
                'admin_request_status' => 'pending',
            ]);

            //Audit Log: Email Change Requested
            AuditLog::create([
                'actor_id' => $user->id,
                'action' => 'REQUEST_EMAIL_CHANGE',
                'entity_type' => 'User',
                'entity_id' => $user->id,
                'description' => "{$user->name} requested to change email to {$request->new_email} (pending admin approval).",
                'ip_address' => request()->ip(),
            ]);

            return back()->with('admin_request_sent', 'email');
        }

        //Client/Owner: send verification link
        $token = Str::random(60);

        $user->update([
            'pending_email' => $request->new_email,
            'pending_email_token' => $token,
        ]);

        $verifyUrl = route('profile.email.verify', ['token' => $token]);

        Mail::raw("Confirm your new email by clicking: {$verifyUrl}", function ($message) use ($request) {
            $message->to($request->new_email)->subject('Confirm Your New Email - Housing 4 Raajje');
        });

        //Audit Log: Email Change Initiated
        AuditLog::create([
            'actor_id' => $user->id,
            'action' => 'INITIATE_EMAIL_CHANGE',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "{$user->name} initiated an email change to {$request->new_email} (awaiting verification).",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', 'A verification link has been sent to your new email address!');
    }


    //Verify the new email via token
    public function verifyEmailChange(Request $request, string $token)
    {
        $user = User::where('pending_email_token', $token)->first();

        if (! $user) {
            return redirect()->route('profile.edit')->withErrors(['email' => 'Invalid or expired verification link.']);
        }

        $oldEmail = $user->email;

        $user->update([
            'email' => $user->pending_email,
            'pending_email' => null,
            'pending_email_token' => null,
        ]);

        //Audit Log: Email Confirmed
        AuditLog::create([
            'actor_id' => $user->id,
            'action' => 'CONFIRM_EMAIL_CHANGE',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "{$user->name} confirmed email change from {$oldEmail} to {$user->email}.",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('profile.edit')->with('status', 'Email address updated successfully!');
    }



    //delete account
    public function destroy(Request $request)
    {
        $request->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $name = $user->name;
        $email = $user->email;

        //Audit Log: Account Deleted (before deletion so we keep the info)
        AuditLog::create([
            'actor_id' => $user->id,
            'action' => 'DELETE_OWN_ACCOUNT',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "{$name} deleted their own account ({$email}).",
            'ip_address' => request()->ip(),
        ]);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been permanently deleted.');
    }
}