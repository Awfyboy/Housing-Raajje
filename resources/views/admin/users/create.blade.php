@extends('admin.layouts.master')

@section('title', 'Add User')

@section('content')
<div class="page-heading">
    <div class="eyebrow">User Management</div>
    <h1 class="page-title">Add New User</h1>
    <p class="page-description">Create accounts for staff, clients, or property owners.</p>
</div>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert small mb-4" style="padding: 1rem; border-radius: 0.5rem; background: #ffdad6; color: #ba1a1a;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label fw-bold small">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Temporary Password</label>
            <input type="text" name="password" class="form-control" value="{{ Str::random(12) }}" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
            <small class="text-muted">Copy this and share it with the user.</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Role</label>
            <select name="role" id="roleSelect" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                <option value="">Select a role...</option>
                <optgroup label="System Administrators">
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>System Administrator</option>
                </optgroup>                
                <optgroup label="Internal Staff">
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff / Property Specialist</option>
                    <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                    <option value="branch_manager" {{ old('role') == 'branch_manager' ? 'selected' : '' }}>Branch Manager</option>
                </optgroup>
                <optgroup label="External Users">
                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client / Tenant</option>
                    <option value="property_owner" {{ old('role') == 'property_owner' ? 'selected' : '' }}>Property Owner</option>
                </optgroup>
            </select>
        </div>

        {{-- 🆕 Staff-only fields (hidden by default) --}}
        <div id="staffFields" style="display: none; border-top: 1px solid #eee; padding-top: 1rem; margin-top: 1rem;">
            <div class="mb-3">
                <label class="form-label fw-bold small">Assigned Branch</label>
                <select name="branch_id" class="form-control" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                    <option value="">Select a branch...</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small">Employee Number</label>
                <input type="text" name="employee_number" class="form-control" value="{{ old('employee_number') }}" placeholder="e.g., STF-004" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Create User Account</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </div>
    </form>
</div>

{{-- 🆕 JavaScript to toggle fields based on role --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('roleSelect');
        const staffFields = document.getElementById('staffFields');

        function toggleStaffFields() {
            const staffRoles = ['staff', 'receptionist', 'branch_manager'];
            if (staffRoles.includes(roleSelect.value)) {
                staffFields.style.display = 'block';
            } else {
                staffFields.style.display = 'none';
            }
        }

        // Run on change and on page load (for old() values)
        roleSelect.addEventListener('change', toggleStaffFields);
        toggleStaffFields();
    });
</script>
@endsection