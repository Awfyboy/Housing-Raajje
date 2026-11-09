<?php

namespace Tests\Feature\Admin;

use App\Enums\BranchLocations;
use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Entity\Branch;
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

    // multiple branches per island
    public function test_admin_can_create_multiple_branches_on_same_island()
    {
        $admin = User::find(1);

        $this->actingAs($admin)->post('/admin/branches', [
            'name' => 'Malé Central',
            'location' => 'male',
            'address' => 'Address 1',
        ]);

        $this->actingAs($admin)->post('/admin/branches', [
            'name' => 'Malé West',
            'location' => 'male',
            'address' => 'Address 2',
        ]);

        $this->assertEquals(2, Branch::where('location', 'male')->count());
    }

    // manager sync
    public function test_assigning_manager_updates_both_sides()
    {
        $admin = User::find(1);
        
        // create branch manually
        $branch = Branch::create([
            'name' => 'Test Branch',
            'location' => BranchLocations::MALE,
            'address' => 'Test Address',
        ]);
        
        // create a manager without a branch
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