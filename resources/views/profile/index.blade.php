{{-- Dynamically pick the layout based on the user's role --}}
@php
    $layout = match(auth()->user()->role) {
        \App\Enums\UserRoles::ADMIN => 'admin.layouts.master',
        \App\Enums\UserRoles::BRANCH_MANAGER => 'branch-manager.layouts.master',
        \App\Enums\UserRoles::STAFF => 'staff.layouts.master',
        \App\Enums\UserRoles::RECEPTIONIST => 'receptionist.layouts.master',
        \App\Enums\UserRoles::PROPERTY_OWNER => 'owner.layouts.master',
        \App\Enums\UserRoles::CLIENT => 'client.layouts.master',
        default => 'client.layouts.master',
    };
@endphp

@extends($layout)

@section('title', 'My Profile')
@section('activeNav', 'profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Global Success Message --}}
            @if (session('status'))
                <div class="alert alert-success small mb-4" style="padding: 1rem; border-radius: 0.5rem; background: #e5f5ec; color: #33825a; border: 1px solid #46a773;">
                    ✅ {{ session('status') }}
                </div>
            @endif

            {{-- Global Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger small mb-4" style="padding: 1rem; border-radius: 0.5rem; background: #ffdad6; color: #ba1a1a; border: 1px solid #ba1a1a;">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{--PROFILE HEADER & MAIN DETAILS--}}
            <div class="d-flex align-items-center mb-4 border-bottom pb-4">
                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-3" 
                    style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold; flex-shrink: 0; 
                            display: inline-flex; align-items: center; justify-content: center;
                            border-radius: 50%; background: #d8b7a5; color: #281a15;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-display mb-0">{{ auth()->user()->name }}</h2>
                    <p class="text-muted mb-0">{{ auth()->user()->role->label() }}</p>
                </div>
            </div>

            {{-- Main Details Form --}}
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold small">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                </div>

                {{--Read-Only Email Display --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email Address</label>
                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly
                           style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd; background: #f4f7fb; color: #71829e; cursor: not-allowed;">
                    <small class="text-muted">To change your email, use the "Change Email Address" section below.</small>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-bold small">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                           style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                </div>

                {{-- Client-Specific Fields --}}
                @if (auth()->user()->role === \App\Enums\UserRoles::CLIENT)
                    <div class="border-top pt-4 mt-4">
                        <h5 class="font-display mb-3">Client Preferences</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Gender</label>
                                <select name="gender" class="form-control" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                                    <option value="">Select...</option>
                                    <option value="male" {{ old('gender', auth()->user()->clientProfile?->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', auth()->user()->clientProfile?->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', auth()->user()->clientProfile?->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" 
                                       value="{{ old('date_of_birth', auth()->user()->clientProfile?->date_of_birth?->format('Y-m-d')) }}"
                                       style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Maximum Rent (MVR)</label>
                            <input type="number" class="form-control" name="maximum_rent" value="{{ old('maximum_rent', auth()->user()->clientProfile?->maximum_rent) }}"
                                   style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Preferred Location</label>
                            <input type="text" class="form-control" name="preferred_location" value="{{ old('preferred_location', auth()->user()->clientProfile?->preferred_location) }}"
                                   style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Preferred Rooms</label>
                                <input type="number" class="form-control" name="preferred_rooms" value="{{ old('preferred_rooms', auth()->user()->clientProfile?->preferred_rooms) }}"
                                       placeholder="e.g., 3"
                                       style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Preferred Lease (Months)</label>
                                <input type="number" class="form-control" name="preferred_lease" value="{{ old('preferred_lease', auth()->user()->clientProfile?->preferred_lease) }}"
                                       placeholder="e.g., 12"
                                       style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Owner-Specific Fields --}}
                @if (auth()->user()->role === \App\Enums\UserRoles::PROPERTY_OWNER)
                    <div class="border-top pt-4 mt-4">
                        <h5 class="font-display mb-3">Property Owner Details</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">National ID / Passport</label>
                            <input type="text" class="form-control" name="national_id" value="{{ old('national_id', auth()->user()->ownerProfile?->national_id) }}"
                                   style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Bank Account Details</label>
                            <input type="text" class="form-control" name="bank_account" value="{{ old('bank_account', auth()->user()->ownerProfile?->bank_account) }}"
                                   style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Residential / Contact Address</label>
                            <textarea name="address" class="form-control" rows="2"
                                      style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">{{ old('address', auth()->user()->ownerProfile?->address) }}</textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="verified_phone" id="verified_phone" value="1" 
                                   {{ old('verified_phone', auth()->user()->ownerProfile?->verified_phone) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="verified_phone">
                                My phone number is verified for contact
                            </label>
                        </div>
                    </div>
                @endif

                <div class="mt-4">
                    <button type="submit" class="btn btn-hr w-100" style="padding: 0.75rem 1.25rem; border-radius: 0.5rem; background: #432d24; color: #fff; font-weight: 800; border: 0; cursor: pointer;">
                        Update Profile
                    </button>
                </div>
            </form>

            {{--CHANGE PASSWORD--}}
            <div class="border-top pt-4 mt-5">
                <h5 class="font-display mb-3">Change Password</h5>

                @if (session('admin_request_sent') === 'password')
                    <div class="alert small mb-3" style="padding: 1rem; border-radius: 0.5rem; background: #e7f1ff; color: #0a58ca; border: 1px solid #9ec5fe;">
                        📩 Your password change request has been sent to the admin for approval.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required
                               style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">New Password</label>
                        <input type="password" class="form-control" name="password" required
                               style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Confirm New Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required
                               style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                    </div>

                    <button type="submit" class="btn btn-hr w-100" style="padding: 0.75rem; border-radius: 0.5rem; background: #432d24; color: #fff; font-weight: 800; border: 0; cursor: pointer;">
                        Update Password
                    </button>
                </form>
            </div>

            {{--MFA TOGGLE--}}
            <div class="border-top pt-4 mt-5">
                <h5 class="font-display mb-3">Security</h5>

                <div class="d-flex justify-content-between align-items-center p-3" style="background: #f8f9fa; border-radius: 0.5rem; border: 1px solid #ddd;">
                    <div>
                        <div class="fw-bold small">Two-Factor Authentication (Email)</div>
                        <div class="text-muted small">Require a 6-digit code sent to your email on every login.</div>
                    </div>
                    <form method="POST" action="{{ route('profile.mfa.toggle') }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: {{ auth()->user()->mfa_enabled ? '#33825a' : '#6c757d' }}; color: #fff; border: 0; font-weight: 700; cursor: pointer; min-width: 100px;">
                            {{ auth()->user()->mfa_enabled ? 'Enabled' : 'Disabled' }}
                        </button>
                    </form>
                </div>
            </div>

            {{--CHANGE EMAIL--}}
            <div class="border-top pt-4 mt-5">
                <h5 class="font-display mb-3">Change Email Address</h5>
                <p class="text-muted small mb-3">
                    Current email: <strong>{{ auth()->user()->email }}</strong>
                </p>

                @if (session('status') === 'email-change-initiated')
                    <div class="alert small mb-3" style="padding: 1rem; border-radius: 0.5rem; background: #e7f1ff; color: #0a58ca; border: 1px solid #9ec5fe;">
                        📩 A verification link has been sent to your <strong>new</strong> email address. Click it to confirm the change.
                    </div>
                @endif

                @if (session('admin_request_sent') === 'email')
                    <div class="alert small mb-3" style="padding: 1rem; border-radius: 0.5rem; background: #e7f1ff; color: #0a58ca; border: 1px solid #9ec5fe;">
                        📩 Your email change request has been sent to the admin for approval.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.email.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold small">New Email Address</label>
                        <input type="email" class="form-control" name="new_email" required
                               style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                    </div>

                    <button type="submit" class="btn btn-hr w-100" style="padding: 0.75rem; border-radius: 0.5rem; background: #432d24; color: #fff; font-weight: 800; border: 0; cursor: pointer;">
                        Request Email Change
                    </button>
                </form>
            </div>
            {{--DELETE ACCOUNT--}}
            <div class="border-top pt-4 mt-5 mb-5">
                <h5 class="font-display mb-3" style="color: #ba1a1a;">Danger Zone</h5>
                <p class="small text-muted">Once you delete your account, all your data will be permanently removed. This action cannot be undone.</p>

                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you absolutely sure? This cannot be undone!');">
                    @csrf
                    @method('DELETE')

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Confirm Password to Delete</label>
                        <input type="password" class="form-control" name="delete_password" required
                               style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                    </div>

                    <button type="submit" style="padding: 0.75rem 1.25rem; border-radius: 0.5rem; background: #ba1a1a; color: #fff; font-weight: 800; border: 0; cursor: pointer;">
                        Delete My Account
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection