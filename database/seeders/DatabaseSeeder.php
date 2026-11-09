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
        | 1. Create the branches
        |--------------------------------------------------------------------------
        */

        $this->call(BranchSeeder::class);

        // Map each branch NAME to its actual database branch ID.
        $branchIds = [];

        foreach (Branch::all() as $branch) {
            $branchIds[$branch->name] = $branch->id;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Define the users
        |--------------------------------------------------------------------------
        */

        $users = [
            // Administrators: no branch assignment.
            [
                'name' => 'Admin One', //Keep this as first in the array as ID 1 would be super admin
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

            // Branch Managers: one for every branch (5 branches = 5 managers)
            [
                'name' => 'Branch Manager One',
                'email' => 'bm1@housing4raajje.mv',
                'phone' => '7000004',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_name' => 'Malé Central Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Branch Manager Two',
                'email' => 'bm2@housing4raajje.mv',
                'phone' => '7000005',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_name' => 'Malé West Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Branch Manager Three',
                'email' => 'bm3@housing4raajje.mv',
                'phone' => '7000006',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_name' => 'Hulhumalé Phase 1 Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Branch Manager Four', 
                'email' => 'bm4@housing4raajje.mv',
                'phone' => '7000019',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_name' => 'Hulhumalé Phase 2 Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Branch Manager Five', 
                'email' => 'bm5@housing4raajje.mv',
                'phone' => '7000020',
                'role' => UserRoles::BRANCH_MANAGER,
                'branch_name' => 'Villingili Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Staff: one per branch
            [
                'name' => 'Staff One',
                'email' => 'staff1@housing4raajje.mv',
                'phone' => '7000007',
                'role' => UserRoles::STAFF,
                'branch_name' => 'Malé Central Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Staff Two',
                'email' => 'staff2@housing4raajje.mv',
                'phone' => '7000008',
                'role' => UserRoles::STAFF,
                'branch_name' => 'Malé West Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Staff Three',
                'email' => 'staff3@housing4raajje.mv',
                'phone' => '7000009',
                'role' => UserRoles::STAFF,
                'branch_name' => 'Hulhumalé Phase 1 Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Receptionists: one per branch
            [
                'name' => 'Receptionist One',
                'email' => 'receptionist1@housing4raajje.mv',
                'phone' => '7000010',
                'role' => UserRoles::RECEPTIONIST,
                'branch_name' => 'Malé Central Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Receptionist Two',
                'email' => 'receptionist2@housing4raajje.mv',
                'phone' => '7000011',
                'role' => UserRoles::RECEPTIONIST,
                'branch_name' => 'Malé West Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Receptionist Three',
                'email' => 'receptionist3@housing4raajje.mv',
                'phone' => '7000012',
                'role' => UserRoles::RECEPTIONIST,
                'branch_name' => 'Hulhumalé Phase 1 Branch',
                'mfa_enabled' => 1,
                'email_verified_at' => now(),
            ],

            // Property Owners: no branch assignment.
            [
                'name' => 'Property Owner One',
                'email' => 'owner1@housing4raajje.mv',
                'phone' => '7000013',
                'role' => UserRoles::PROPERTY_OWNER,
                'mfa_enabled' => 0,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Property Owner Two',
                'email' => 'owner2@housing4raajje.mv',
                'phone' => '7000014',
                'role' => UserRoles::PROPERTY_OWNER,
                'mfa_enabled' => 0,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Property Owner Three',
                'email' => 'owner3@housing4raajje.mv',
                'phone' => '7000015',
                'role' => UserRoles::PROPERTY_OWNER,
                'mfa_enabled' => 0,
                'email_verified_at' => now(),
            ],

            // Clients: no branch assignment.
            [
                'name' => 'Client One',
                'email' => 'client1@housing4raajje.mv',
                'phone' => '7000016',
                'role' => UserRoles::CLIENT,
                'mfa_enabled' => 0,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Client Two',
                'email' => 'client2@housing4raajje.mv',
                'phone' => '7000017',
                'role' => UserRoles::CLIENT,
                'mfa_enabled' => 0,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Client Three',
                'email' => 'client3@housing4raajje.mv',
                'phone' => '7000018',
                'role' => UserRoles::CLIENT,
                'mfa_enabled' => 0,
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

            // Resolve the branch name to the actual branches.id value
            $branchId = isset($userData['branch_name'])
                ? $branchIds[$userData['branch_name']]
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
                        'verified_phone' => false, // 🆕 Added for consistency
                    ]
                ),

                // Keep Client-specific properties NULL.
                UserRoles::CLIENT => Client::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'full_name' => $userData['name'], //REQUIRED(Not Null in DB)
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
        | 4. Assign each Branch Manager to their branch (by NAME)
        |--------------------------------------------------------------------------
        */

        $managerAssignments = [
            'Malé Central Branch' => 'bm1@housing4raajje.mv',
            'Malé West Branch' => 'bm2@housing4raajje.mv',
            'Hulhumalé Phase 1 Branch' => 'bm3@housing4raajje.mv',
            'Hulhumalé Phase 2 Branch' => 'bm4@housing4raajje.mv', 
            'Villingili Branch' => 'bm5@housing4raajje.mv',        
        ];

        foreach ($managerAssignments as $branchName => $email) {
            $manager = User::where('email', $email)->firstOrFail();
            $branch = Branch::where('name', $branchName)->firstOrFail();

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