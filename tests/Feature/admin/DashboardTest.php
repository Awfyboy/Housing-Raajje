<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    // dashboard loads with correct counts
    public function test_admin_dashboard_loads_with_correct_counts()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        
        User::factory()->count(3)->create(['role' => UserRoles::STAFF]);
        User::factory()->count(2)->create(['role' => UserRoles::CLIENT]);
        User::factory()->count(1)->create(['role' => UserRoles::PROPERTY_OWNER]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewHas('totalUsers', 7); // 1 admin + 3 staff + 2 clients + 1 owner
        $response->assertViewHas('totalStaff', 3);
        $response->assertViewHas('totalClients', 2);
        $response->assertViewHas('totalOwners', 1);
    }

    // dashboard shows pending requests count
    public function test_admin_dashboard_shows_pending_requests_count()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        
        User::factory()->count(2)->create([
            'role' => UserRoles::STAFF,
            'admin_request_status' => 'pending',
            'admin_request_type' => 'password_change',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertViewHas('pendingRequestsCount', 2);
    }
}