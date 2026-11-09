<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Entity\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'role' => UserRoles::ADMIN,
        ]);
    }

    public function test_admin_can_view_audit_logs_page()
    {
        $admin = User::find(1);
        AuditLog::create([
            'actor_id' => $admin->id,
            'action' => 'LOGIN',
            'entity_type' => 'User',
            'entity_id' => $admin->id,
            'description' => 'Admin logged in.',
            'ip_address' => '127.0.0.1',
        ]);

        $response = $this->actingAs($admin)->get('/admin/audit-logs');

        $response->assertOk();
        $response->assertSee('Admin logged in.');
    }

    public function test_admin_can_filter_logs_by_user()
    {
        $admin = User::find(1);
        $other = User::factory()->create(['role' => UserRoles::CLIENT]);

        AuditLog::create([
            'actor_id' => $admin->id,
            'action' => 'LOGIN',
            'entity_type' => 'User',
            'entity_id' => $admin->id,
            'description' => 'Admin login.',
        ]);
        AuditLog::create([
            'actor_id' => $other->id,
            'action' => 'UPDATE_PROFILE',
            'entity_type' => 'User',
            'entity_id' => $other->id,
            'description' => 'Client updated profile.',
        ]);

        $response = $this->actingAs($admin)->get("/admin/audit-logs?actor_id={$admin->id}");

        $response->assertSee('Admin login.');
        $response->assertDontSee('Client updated profile.');
    }

    public function test_admin_can_filter_logs_by_action()
    {
        $admin = User::find(1);

        AuditLog::create([
            'actor_id' => $admin->id,
            'action' => 'LOGIN',
            'entity_type' => 'User',
            'entity_id' => $admin->id,
            'description' => 'First login.',
        ]);
        AuditLog::create([
            'actor_id' => $admin->id,
            'action' => 'SUSPEND_USER',
            'entity_type' => 'User',
            'entity_id' => $admin->id,
            'description' => 'Suspended someone.',
        ]);

        $response = $this->actingAs($admin)->get('/admin/audit-logs?action=LOGIN');

        $response->assertSee('First login.');
        $response->assertDontSee('Suspended someone.');
    }

    public function test_admin_can_filter_logs_by_role()
    {
        $admin = User::find(1);
        $staff = User::factory()->create(['role' => UserRoles::STAFF]);

        AuditLog::create([
            'actor_id' => $admin->id,
            'action' => 'LOGIN',
            'entity_type' => 'User',
            'entity_id' => $admin->id,
            'description' => 'Admin action.',
        ]);
        AuditLog::create([
            'actor_id' => $staff->id,
            'action' => 'LOGIN',
            'entity_type' => 'User',
            'entity_id' => $staff->id,
            'description' => 'Staff action.',
        ]);

        $response = $this->actingAs($admin)->get('/admin/audit-logs?role=staff');

        $response->assertSee('Staff action.');
        $response->assertDontSee('Admin action.');
    }

    public function test_failed_login_creates_system_log()
    {
        AuditLog::create([
            'actor_id' => null,
            'action' => 'FAILED_LOGIN',
            'entity_type' => 'User',
            'entity_id' => null,
            'description' => 'Failed login attempt for unknown@test.com.',
        ]);

        $admin = User::find(1);
        $response = $this->actingAs($admin)->get('/admin/audit-logs?role=system');

        $response->assertSee('Failed login attempt');
        $response->assertSee('System');
    }
}