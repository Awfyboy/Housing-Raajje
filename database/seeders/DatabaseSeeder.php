<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            // Admin (3)
            [
                'name' => 'Admin One',
                'email' => 'admin1@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::ADMIN,
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::ADMIN,
            ],
            [
                'name' => 'Admin Three',
                'email' => 'admin3@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::ADMIN,
            ],

            // Branch Manager (3)
            [
                'name' => 'Branch Manager One',
                'email' => 'bm1@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::BRANCH_MANAGER,
            ],
            [
                'name' => 'Branch Manager Two',
                'email' => 'bm2@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::BRANCH_MANAGER,
            ],
            [
                'name' => 'Branch Manager Three',
                'email' => 'bm3@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::BRANCH_MANAGER,
            ],

            // Staff (3)
            [
                'name' => 'Staff One',
                'email' => 'staff1@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::STAFF,
            ],
            [
                'name' => 'Staff Two',
                'email' => 'staff2@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::STAFF,
            ],
            [
                'name' => 'Staff Three',
                'email' => 'staff3@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::STAFF,
            ],

            // Receptionist (3)
            [
                'name' => 'Receptionist One',
                'email' => 'receptionist1@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::RECEPTIONIST,
            ],
            [
                'name' => 'Receptionist Two',
                'email' => 'receptionist2@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::RECEPTIONIST,
            ],
            [
                'name' => 'Receptionist Three',
                'email' => 'receptionist3@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::RECEPTIONIST,
            ],

            // Property Owner (3)
            [
                'name' => 'Property Owner One',
                'email' => 'owner1@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::PROPERTY_OWNER,
            ],
            [
                'name' => 'Property Owner Two',
                'email' => 'owner2@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::PROPERTY_OWNER,
            ],
            [
                'name' => 'Property Owner Three',
                'email' => 'owner3@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::PROPERTY_OWNER,
            ],

            // Client (3)
            [
                'name' => 'Client One',
                'email' => 'client1@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::CLIENT,
            ],
            [
                'name' => 'Client Two',
                'email' => 'client2@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::CLIENT,
            ],
            [
                'name' => 'Client Three',
                'email' => 'client3@housing4raajje.mv',
                'password' => 'password123',
                'role' => UserRoles::CLIENT,
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'role' => $user['role'],
            ]);
        }
    }
}
