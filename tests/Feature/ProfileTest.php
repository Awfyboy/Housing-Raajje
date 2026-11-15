<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // profile update
    public function test_client_can_update_their_profile()
    {
        $client = User::factory()->create(['role' => UserRoles::CLIENT]);

        $response = $this->actingAs($client)->put('/profile', [
            'name' => 'Updated Name',
            'phone' => '7777777',
            'gender' => 'female',
            'date_of_birth' => '1995-05-10',
            'maximum_rent' => 15000,
            'preferred_location' => 'Malé',
            'preferred_rooms' => 3,
            'preferred_lease' => 12,
        ]);

        $response->assertRedirect();
        
        $client->refresh();
        $this->assertEquals('Updated Name', $client->name);
        $this->assertEquals('female', $client->clientProfile->gender);
        $this->assertEquals('1995-05-10', $client->clientProfile->date_of_birth->format('Y-m-d'));
        $this->assertEquals(15000, $client->clientProfile->maximum_rent);
        $this->assertEquals('Malé', $client->clientProfile->preferred_location);
        $this->assertEquals(3, $client->clientProfile->preferred_rooms);
        $this->assertEquals(12, $client->clientProfile->preferred_lease);
    }

    public function test_owner_can_update_owner_specific_fields()
    {
        $owner = User::factory()->create(['role' => UserRoles::PROPERTY_OWNER]);

        $this->actingAs($owner)->put('/profile', [
            'name' => 'Owner Updated',
            'phone' => '7777777',
            'national_id' => 'A1234567',
            'bank_account' => 'BML-123456',
            'address' => 'Test Address, Malé',
            'verified_phone' => true,
        ]);

        $owner->refresh();
        $this->assertEquals('A1234567', $owner->ownerProfile->national_id);
        $this->assertEquals('BML-123456', $owner->ownerProfile->bank_account);
        $this->assertEquals('Test Address, Malé', $owner->ownerProfile->address);
        $this->assertTrue((bool) $owner->ownerProfile->verified_phone);
    }

    // password changes
    public function test_client_password_changes_instantly()
    {
        $client = User::factory()->create([
            'role' => UserRoles::CLIENT,
            'password' => bcrypt('oldpass123'),
        ]);

        $response = $this->actingAs($client)->put('/profile/password', [
            'current_password' => 'oldpass123',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('newpass123', $client->fresh()->password));
        $this->assertEquals('CHANGE_PASSWORD', \App\Models\Entity\AuditLog::where('action', 'CHANGE_PASSWORD')->first()->action);
    }

    public function test_staff_password_change_creates_pending_request()
    {
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'password' => bcrypt('oldpass123'),
        ]);

        $this->actingAs($staff)->put('/profile/password', [
            'current_password' => 'oldpass123',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);

        $this->assertEquals('pending', $staff->fresh()->admin_request_status);
        $this->assertEquals('password_change', $staff->fresh()->admin_request_type);
    }

    // mfa toggle
    public function test_user_can_toggle_mfa()
    {
        $user = User::factory()->create(['mfa_enabled' => true]);

        $this->actingAs($user)->put('/profile/mfa');
        $this->assertFalse($user->fresh()->mfa_enabled);

        $this->actingAs($user)->put('/profile/mfa');
        $this->assertTrue($user->fresh()->mfa_enabled);
    }

    // email change
    public function test_client_email_change_sends_verification_link()
    {
        Mail::fake();

        $client = User::factory()->create(['role' => UserRoles::CLIENT]);

        $this->actingAs($client)->put('/profile/email', [
            'new_email' => 'newclient@test.com',
        ]);

        $client->refresh();
        $this->assertEquals('newclient@test.com', $client->pending_email);
        $this->assertNotNull($client->pending_email_token);
    }

    public function test_email_verification_updates_user_email()
    {
        $client = User::factory()->create(['role' => UserRoles::CLIENT]);

        $client->update([
            'pending_email' => 'verified@test.com',
            'pending_email_token' => 'test-token-123',
        ]);

        // user must be logged in to access this route
        $this->actingAs($client)->get('/profile/email/verify/test-token-123');

        $this->assertEquals('verified@test.com', $client->fresh()->email);
        $this->assertNull($client->fresh()->pending_email);
    }

    public function test_staff_email_change_creates_pending_request()
    {
        $staff = User::factory()->create(['role' => UserRoles::STAFF]);

        $this->actingAs($staff)->put('/profile/email', [
            'new_email' => 'newstaff@test.com',
        ]);

        $this->assertEquals('pending', $staff->fresh()->admin_request_status);
        $this->assertEquals('email_change', $staff->fresh()->admin_request_type);
    }

    // account deletion
    public function test_user_can_delete_their_own_account()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);
        $userId = $user->id;

        $this->actingAs($user)->delete('/profile', [
            'delete_password' => 'password123',
        ]);

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_user_cannot_delete_with_wrong_password()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $response = $this->actingAs($user)->delete('/profile', [
            'delete_password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('delete_password');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    // request test flow
    public function test_staff_cannot_submit_two_pending_requests()
    {
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'password' => bcrypt('oldpass123'),
        ]);

        // 1. submit password change
        $this->actingAs($staff)->put('/profile/password', [
            'current_password' => 'oldpass123',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);

        $this->assertEquals('pending', $staff->fresh()->admin_request_status);

        // 2. try to submit email change - should be blocked
        $response = $this->actingAs($staff)->put('/profile/email', [
            'new_email' => 'newemail@test.com',
        ]);

        $response->assertSessionHasErrors('new_email');
        $this->assertEquals('password_change', $staff->fresh()->admin_request_type);
        $this->assertNotEquals('newemail@test.com', $staff->fresh()->admin_request_note);
    }

    public function test_admin_approving_password_request_updates_password()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'admin_request_type' => 'password_change',
            'admin_request_note' => bcrypt('newpass123'),
            'admin_request_status' => 'pending',
        ]);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/approve");

        $this->assertTrue(Hash::check('newpass123', $staff->fresh()->password));
        $this->assertNull($staff->fresh()->admin_request_status);
    }

    public function test_admin_approving_email_request_updates_email()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'admin_request_type' => 'email_change',
            'admin_request_note' => 'newemail@test.com',
            'admin_request_status' => 'pending',
        ]);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/approve");

        $this->assertEquals('newemail@test.com', $staff->fresh()->email);
        $this->assertNull($staff->fresh()->admin_request_status);
    }

    public function test_admin_rejecting_request_clears_it()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'admin_request_type' => 'password_change',
            'admin_request_note' => bcrypt('newpass123'),
            'admin_request_status' => 'pending',
        ]);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/reject");

        $this->assertNull($staff->fresh()->admin_request_status);
        $this->assertFalse(Hash::check('newpass123', $staff->fresh()->password));
    }

    public function test_suspending_user_clears_pending_request()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $staff = User::factory()->create([
            'role' => UserRoles::STAFF,
            'admin_request_type' => 'password_change',
            'admin_request_note' => 'something',
            'admin_request_status' => 'pending',
        ]);

        $this->actingAs($admin)->post("/admin/users/{$staff->id}/suspend");

        $this->assertNull($staff->fresh()->admin_request_status);
        $this->assertEquals('suspended', $staff->fresh()->status);
    }

    // role verification tests
    public function test_staff_cannot_update_client_specific_fields()
    {
        $staff = User::factory()->create(['role' => UserRoles::STAFF]);

        $this->actingAs($staff)->put('/profile', [
            'name' => 'Staff Updated',
            'phone' => '7777777',
            'maximum_rent' => 15000, // client field - should be ignored
            'preferred_location' => 'Malé', // client field - should be ignored
        ]);

        $this->assertEquals('Staff Updated', $staff->fresh()->name);
        $this->assertNull($staff->fresh()->clientProfile);
    }

    public function test_client_does_not_get_owner_profile_created()
    {
        $client = User::factory()->create(['role' => UserRoles::CLIENT]);

        $this->actingAs($client)->put('/profile', [
            'name' => 'Client Updated',
            'phone' => '7777777',
            'national_id' => 'A1234567', // owner field - should be ignored
            'bank_account' => 'BML-123', // owner field - should be ignored
        ]);

        $this->assertEquals('Client Updated', $client->fresh()->name);
        $this->assertNull($client->fresh()->ownerProfile);
    }

    public function test_client_full_name_is_synced_with_user_name()
    {
        $client = User::factory()->create(['role' => UserRoles::CLIENT]);

        $this->actingAs($client)->put('/profile', [
            'name' => 'John Doe',
            'phone' => '7777777',
        ]);

        // the client's profile should have full_name synced
        $this->assertEquals('John Doe', $client->fresh()->clientProfile->full_name);
    }
}