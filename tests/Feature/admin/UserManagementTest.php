<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Entity\Branch;
use App\Models\Entity\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Super Admin (id = 1)
        User::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'role' => UserRoles::ADMIN,
        ]);
    }


    //search and filter
    public function test_admin_can_search_users_by_name()
    {
        $admin = User::find(1);
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@test.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@test.com']);

        $response = $this->actingAs($admin)->get('/admin/users?search=John');

        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    public function test_admin_can_filter_users_by_role()
    {
        $admin = User::find(1);
        User::factory()->create(['role' => UserRoles::CLIENT]);
        User::factory()->create(['role' => UserRoles::STAFF]);

        $response = $this->actingAs($admin)->get('/admin/users?role=staff');

        $response->assertViewHas('users', function ($users) {
            return $users->every(fn ($u) => $u->role === UserRoles::STAFF);
        });
    }


    //suspend and reactivate
    public function test_admin_can_suspend_a_user()
    {
        $admin = User::find(1);
        $staff = User::factory()->create(['role' => UserRoles::STAFF, 'status' => 'active']);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/suspend");

        $this->assertEquals('suspended', $staff->fresh()->status);
        $this->assertDatabaseHas('audit_logs', ['action' => 'SUSPEND_USER']);
    }

    public function test_admin_can_reactivate_a_user()
    {
        $admin = User::find(1);
        $staff = User::factory()->create(['role' => UserRoles::STAFF, 'status' => 'suspended']);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/reactivate");

        $this->assertEquals('active', $staff->fresh()->status);
        $this->assertDatabaseHas('audit_logs', ['action' => 'REACTIVATE_USER']);
    }

    //Role changes
    public function test_super_admin_cannot_be_demoted()
    {
        $admin = User::find(1);

        $response = $this->actingAs($admin)->put("/admin/users/1", [
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertEquals(UserRoles::ADMIN, $admin->fresh()->role);
    }

    public function test_regular_admin_cannot_demote_another_admin()
    {
        $admin2 = User::factory()->create([
            'role' => UserRoles::ADMIN,
            'email' => 'admin2@test.com',
        ]);

        $response = $this->actingAs($admin2)->put("/admin/users/1", [
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_admin_demoting_themselves_is_logged_out()
    {
        $admin2 = User::factory()->create([
            'role' => UserRoles::ADMIN,
            'email' => 'admin2@test.com',
        ]);

        $response = $this->actingAs($admin2)->put("/admin/users/{$admin2->id}", [
            'name' => 'Admin Two',
            'email' => 'admin2@test.com',
            'role' => 'client',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    //suspend and delete safety
    public function test_super_admin_cannot_be_suspended()
    {
        $admin = User::find(1);
        $admin2 = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin2)->post("/admin/users/1/suspend");

        $response->assertSessionHasErrors('user');
        $this->assertEquals('active', $admin->fresh()->status);
    }

    public function test_super_admin_cannot_be_deleted()
    {
        $admin = User::find(1);
        $admin2 = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin2)->delete("/admin/users/1");

        $response->assertSessionHasErrors('user');
        $this->assertDatabaseHas('users', ['id' => 1]);
    }

    //approve and reject requests
    public function test_admin_can_approve_password_request()
    {
        $admin = User::find(1);
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'admin_request_status' => 'pending',
            'admin_request_type' => 'password_change',
            'admin_request_note' => bcrypt('newpass123'),
        ]);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/approve");

        $this->assertTrue(\Hash::check('newpass123', $staff->fresh()->password));
        $this->assertNull($staff->fresh()->admin_request_status);
    }

    public function test_admin_can_reject_email_request()
    {
        $admin = User::find(1);
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'admin_request_status' => 'pending',
            'admin_request_type' => 'email_change',
            'admin_request_note' => 'newemail@test.com',
        ]);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/reject");

        $this->assertNotEquals('newemail@test.com', $staff->fresh()->email);
        $this->assertNull($staff->fresh()->admin_request_status);
    }
}