@extends('admin.layouts.master')

@section('title', 'Edit User')

@section('content')
<div class="page-heading">
    <div class="eyebrow">User Management</div>
    <h1 class="page-title">Edit: {{ $user->name }}</h1>
    <p class="page-description">Update this user's details. Role and branch changes take effect immediately.</p>
</div>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

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
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Role</label>
            <select name="role" id="roleSelect" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                @foreach ($roles as $role)
                    <option value="{{ $role->value }}" {{ old('role', $user->role->value) == $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="staffFields" style="display: none; border-top: 1px solid #eee; padding-top: 1rem; margin-top: 1rem;">
            <div class="mb-3">
                <label class="form-label fw-bold small">Assigned Branch</label>
                <select name="branch_id" class="form-control" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                    <option value="">Select a branch...</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('roleSelect');
        const staffFields = document.getElementById('staffFields');
        function toggle() {
            const staffRoles = ['staff', 'receptionist', 'branch_manager'];
            staffFields.style.display = staffRoles.includes(roleSelect.value) ? 'block' : 'none';
        }
        roleSelect.addEventListener('change', toggle);
        toggle();
    });
</script>
@endsection