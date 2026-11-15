<?php

namespace Tests\Feature\Admin;

use App\Enums\BranchLocations;
use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Entity\Branch;
use App\Models\Entity\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create([
            'id' => 1,
            'role' => UserRoles::ADMIN,
            'email' => 'super@test.com',
        ]);
    }

    // create branch
    public function test_admin_can_create_a_branch()
    {
        $admin = User::find(1);

        $response = $this->actingAs($admin)->post('/admin/branches', [
            'name' => 'Test Branch',
            'location' => 'male',
            'address' => 'Test Address, Malé',
        ]);

        $response->assertRedirect(route('admin.branches.index'));
        $this->assertDatabaseHas('branches', ['name' => 'Test Branch']);
    }

    // cannot create two branches on same island
    public function test_admin_cannot_create_two_branches_on_same_island()
    {
        $admin = User::find(1);

        // first branch - allowed
        $this->actingAs($admin)->post('/admin/branches', [
            'name' => 'Malé Central',
            'location' => 'male',
            'address' => 'Address 1',
        ]);

        // second branch on same island - should be blocked
        $response = $this->actingAs($admin)->post('/admin/branches', [
            'name' => 'Malé West',
            'location' => 'male',
            'address' => 'Address 2',
        ]);

        $response->assertSessionHasErrors('location');
        $this->assertEquals(1, Branch::where('location', 'male')->count());
    }

    // manager sync
    public function test_assigning_manager_updates_both_sides()
    {
        $admin = User::find(1);
        $branch = Branch::create([
            'name' => 'Test Branch',
            'location' => BranchLocations::MALE,
            'address' => 'Test Address',
        ]);
        $manager = User::factory()->branchManager()->create(['branch_id' => null]);

        $this->actingAs($admin)->put("/admin/branches/{$branch->id}", [
            'name' => 'Test Branch',
            'location' => 'male',
            'address' => 'Test Address',
            'manager_id' => $manager->id,
        ]);

        $branch->refresh();
        $manager->refresh();

        $this->assertEquals($manager->id, $branch->manager_id);
        $this->assertEquals($branch->id, $manager->branch_id);
    }

    //replacing a branch manager
    public function test_replacing_manager_frees_old_and_assigns_new()
    {
        $admin = User::find(1);
        $branch = Branch::create([
            'name' => 'Malé Branch',
            'location' => BranchLocations::MALE,
            'address' => 'Test Address',
        ]);

        // Old manager assigned to the branch
        $oldManager = User::factory()->branchManager()->create(['branch_id' => $branch->id]);
        $branch->update(['manager_id' => $oldManager->id]);

        // New manager - unassigned
        $newManager = User::factory()->branchManager()->create(['branch_id' => null]);

        // Replace manager
        $this->actingAs($admin)->put("/admin/branches/{$branch->id}", [
            'name' => 'Malé Branch',
            'location' => 'male',
            'address' => 'Test Address',
            'manager_id' => $newManager->id,
        ]);

        $branch->refresh();
        $oldManager->refresh();
        $newManager->refresh();

        // new manager takes over the branch
        $this->assertEquals($newManager->id, $branch->manager_id);
        $this->assertEquals($branch->id, $newManager->branch_id);

        // old manager is freed
        $this->assertNull($oldManager->branch_id);

        // audit log recorded
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'REPLACE_BRANCH_MANAGER',
            'entity_id' => $branch->id,
        ]);
    }

    // cannot delete branch with users
    public function test_cannot_delete_branch_with_users()
    {
        $admin = User::find(1);
        $branch = Branch::create([
            'name' => 'Test Branch',
            'location' => BranchLocations::MALE,
            'address' => 'Test Address',
        ]);
        User::factory()->staff()->create(['branch_id' => $branch->id]);

        $response = $this->actingAs($admin)->delete("/admin/branches/{$branch->id}");

        $response->assertSessionHasErrors('branch');
        $this->assertDatabaseHas('branches', ['id' => $branch->id]);
    }

    // can delete empty branch
    public function test_can_delete_branch_with_no_users()
    {
        $admin = User::find(1);
        $branch = Branch::create([
            'name' => 'Test Branch',
            'location' => BranchLocations::MALE,
            'address' => 'Test Address',
        ]);

        $this->actingAs($admin)->delete("/admin/branches/{$branch->id}");

        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }
}