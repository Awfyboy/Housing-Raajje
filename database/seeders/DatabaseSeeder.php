<?php

namespace Database\Seeders;

use App\Enums\BranchLocations;
use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Entity\Branch;
use App\Models\Entity\Admin;
use App\Models\Entity\BranchManager;
use App\Models\Entity\Staff;
use App\Models\Entity\Receptionist;
use App\Models\Entity\Owner;
use App\Models\Entity\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Create the three branches
        |--------------------------------------------------------------------------
        */

        $this->call(BranchSeeder::class);

        // Map each location enum value to its actual database branch ID.
        $branchIds = [];

        foreach (BranchLocations::cases() as $location) {
            $branch = Branch::where(
                'location',
                $location->value
            )->firstOrFail();

            $branchIds[$location->value] = $branch->id;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Define the 18 users
        |--------------------------------------------------------------------------
        */

        $users = [
            // Administrators: no branch assignment.
            [
                'name' => 'Admin One',
                'email' => 'admin1@housing4raajje.mv',
                'phone' => '7000001',
                'role' => UserRoles::ADMIN,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@housing4raajje.mv',
                'phone' => '7000002',
                'role' => UserRoles::ADMIN,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@housing4raajje.mv',
                'phone' => '7000003',
                'role' => UserRoles::ADMIN,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Branch Managers: one per branch.
            [
                'name' => 'Branch Manager One',
                'email' => 'bm1@housing4raajje.mv',
                'phone' => '7000004',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_id' => BranchLocations::MALE,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Branch Manager Two',
                'email' => 'bm2@housing4raajje.mv',
                'phone' => '7000005',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_id' => BranchLocations::VILLINGILI,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Branch Manager Three',
                'email' => 'bm3@housing4raajje.mv',
                'phone' => '7000006',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_id' => BranchLocations::HULHUMALE,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Staff: one per branch.
            [
                'name' => 'Staff One',
                'email' => 'staff1@housing4raajje.mv',
                'phone' => '7000007',
                'role' => UserRoles::STAFF,
                'branch_id' => BranchLocations::MALE,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Staff Two',
                'email' => 'staff2@housing4raajje.mv',
                'phone' => '7000008',
                'role' => UserRoles::STAFF,
                'branch_id' => BranchLocations::VILLINGILI,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Staff Three',
                'email' => 'staff3@housing4raajje.mv',
                'phone' => '7000009',
                'role' => UserRoles::STAFF,
                'branch_id' => BranchLocations::HULHUMALE,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Receptionists: one per branch.
            [
                'name' => 'Receptionist One',
                'email' => 'receptionist1@housing4raajje.mv',
                'phone' => '7000010',
                'role' => UserRoles::RECEPTIONIST,
                'branch_id' => BranchLocations::MALE,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Receptionist Two',
                'email' => 'receptionist2@housing4raajje.mv',
                'phone' => '7000011',
                'role' => UserRoles::RECEPTIONIST,
                'branch_id' => BranchLocations::VILLINGILI,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Receptionist Three',
                'email' => 'receptionist3@housing4raajje.mv',
                'phone' => '7000012',
                'role' => UserRoles::RECEPTIONIST,
                'branch_id' => BranchLocations::HULHUMALE,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Property Owners: no branch assignment.
            [
                'name' => 'Property Owner One',
                'email' => 'owner1@housing4raajje.mv',
                'phone' => '7000013',
                'role' => UserRoles::PROPERTY_OWNER,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Property Owner Two',
                'email' => 'owner2@housing4raajje.mv',
                'phone' => '7000014',
                'role' => UserRoles::PROPERTY_OWNER,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Property Owner Three',
                'email' => 'owner3@housing4raajje.mv',
                'phone' => '7000015',
                'role' => UserRoles::PROPERTY_OWNER,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Clients: no branch assignment.
            [
                'name' => 'Client One',
                'email' => 'client1@housing4raajje.mv',
                'phone' => '7000016',
                'role' => UserRoles::CLIENT,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Client Two',
                'email' => 'client2@housing4raajje.mv',
                'phone' => '7000017',
                'role' => UserRoles::CLIENT,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Client Three',
                'email' => 'client3@housing4raajje.mv',
                'phone' => '7000018',
                'role' => UserRoles::CLIENT,
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | 3. Create users and their corresponding profile models
        |--------------------------------------------------------------------------
        */

        $counters = [
            UserRoles::ADMIN->value => 0,
            UserRoles::BRANCH_MANAGER->value => 0,
            UserRoles::STAFF->value => 0,
            UserRoles::RECEPTIONIST->value => 0,
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            $roleKey = $role->value;

            // Resolve the enum location to the actual branches.id value.
            $branchId = isset($userData['branch_id'])
                ? $branchIds[$userData['branch_id']->value]
                : null;

            // Create or update the shared User record.
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'password' => 'password123',
                    'role' => $role,
                    'branch_id' => $branchId,
                    'status' => 'active',
                    'mfa_enabled' => $userData['mfa_enabled'],
                    'email_verified_at' => $userData['email_verified_at'],
                ]
            );

            /*
             * Admin, Branch Manager, Staff and Receptionist
             * each receive an employee number and active status.
             */
            if (array_key_exists($roleKey, $counters)) {
                $counters[$roleKey]++;
                $number = $counters[$roleKey];
            }

            // Create or update the matching profile.
            match ($role) {
                UserRoles::ADMIN => Admin::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_number' => 'ADM'
                            . str_pad($number, 3, '0', STR_PAD_LEFT),
                        'appointed_at' => now()->subMonths(6 * $number),
                        'employment_status' => 'active',
                    ]
                ),

                UserRoles::BRANCH_MANAGER => BranchManager::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_number' => 'BM'
                            . str_pad($number, 3, '0', STR_PAD_LEFT),
                        'appointed_at' => now()->subMonths(6 * $number),
                        'employment_status' => 'active',
                    ]
                ),

                UserRoles::STAFF => Staff::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_number' => 'STF'
                            . str_pad($number, 3, '0', STR_PAD_LEFT),
                        'hired_at' => now()->subMonths(6 * $number),
                        'employment_status' => 'active',
                    ]
                ),

                UserRoles::RECEPTIONIST => Receptionist::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'employee_number' => 'REC'
                            . str_pad($number, 3, '0', STR_PAD_LEFT),
                        'hired_at' => now()->subMonths(6 * $number),
                        'employment_status' => 'active',
                    ]
                ),

                // Keep Owner-specific properties NULL.
                UserRoles::PROPERTY_OWNER => Owner::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'national_id' => null,
                        'bank_account' => null,
                        'address' => null,
                    ]
                ),

                // Keep Client-specific properties NULL.
                UserRoles::CLIENT => Client::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'gender' => null,
                        'date_of_birth' => null,
                        'phone' => null,
                        'maximum_rent' => null,
                        'preferred_location' => null,
                        'preferred_rooms' => null,
                        'preferred_lease' => null,
                    ]
                ),
            };
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Assign each Branch Manager to their branch
        |--------------------------------------------------------------------------
        */

        $managerAssignments = [
            BranchLocations::MALE->value =>
                'bm1@housing4raajje.mv',

            BranchLocations::VILLINGILI->value =>
                'bm2@housing4raajje.mv',

            BranchLocations::HULHUMALE->value =>
                'bm3@housing4raajje.mv',
        ];

        foreach ($managerAssignments as $location => $email) {
            $manager = User::where('email', $email)->firstOrFail();

            $branch = Branch::where(
                'location',
                $location
            )->firstOrFail();

            // Keep the manager's own branch assignment consistent.
            if ((int) $manager->branch_id !== (int) $branch->id) {
                throw new \RuntimeException(
                    "Branch assignment mismatch for {$email}."
                );
            }

            $branch->update([
                'manager_id' => $manager->id,
            ]);
        }
    }
}
