<?php

namespace Database\Seeders;

use App\Models\Entity\Branch;
use App\Models\Entity\Owner;
use App\Models\Entity\Property;
use App\Models\Entity\PropertyRegistration;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::pluck('id', 'name');   // ['Malé Branch' => 1, ...]
        $owners   = Owner::with('user')->get();    // owner->user->name / ->id
        $admin    = User::where('email', 'admin1@housing4raajje.mv')->first();

        if (! $admin) {
            throw new \RuntimeException(
                'PropertySeeder requires admin1@housing4raajje.mv — run DatabaseSeeder first.'
            );
        }
        if ($owners->isEmpty()) {
            throw new \RuntimeException(
                'PropertySeeder requires at least one Owner — run DatabaseSeeder first.'
            );
        }

        // [owner_index, branch_name, title, address, location,
        //  rent, bedrooms, bathrooms, property_type, status]
        $properties = [
            [
                0, 'Malé Branch', '3-Bedroom Apartment — Henveiru',
                'M. Boduthakurufaanu Magu, Henveiru, 20026',
                'Malé', 18000, 3, 2, 'apartment', Property::STATUS_AVAILABLE,
            ],
            [
                0, 'Malé Branch', '2-Bedroom Flat — Galolhu',
                'G. Aimathu Goalhi, Galolhu, 20027',
                'Malé', 12500, 2, 1, 'flat', Property::STATUS_PENDING,
            ],
            [
                1, 'Hulhumalé Branch', '3-Bedroom Apartment — Phase 1',
                'H. Medhu Goalhi, Phase 1, 23000',
                'Hulhumalé', 20000, 3, 2, 'apartment', Property::STATUS_AVAILABLE,
            ],
            [
                1, 'Hulhumalé Branch', 'Studio — Beach Road',
                'H. Beach Road, 23000',
                'Hulhumalé', 9500, 1, 1, 'studio', Property::STATUS_RENTED,
            ],
            [
                2, 'Villingili Branch', '4-Bedroom House — Ameer Ahmed Magu',
                'V. Ameer Ahmed Magu, 21000',
                'Villingili', 25000, 4, 3, 'house', Property::STATUS_PENDING,
            ],
            [
                2, 'Villingili Branch', '2-Bedroom Apartment — Goalhi 5',
                'V. Goalhi 5, 21000',
                'Villingili', 11000, 2, 1, 'apartment', Property::STATUS_UNAVAILABLE,
            ],
        ];

        foreach ($properties as [
            $ownerIdx, $branchName, $title, $address, $location,
            $rent, $beds, $baths, $type, $status,
        ]) {
            $owner    = $owners[$ownerIdx] ?? $owners->first();
            $branchId = $branches[$branchName] ?? null;

            $property = Property::updateOrCreate(
                ['title' => $title, 'owner_id' => $owner->id],
                [
                    'branch_id'     => $branchId,
                    'description'   => "Seed property for {$owner->user->name}.",
                    'address'       => $address,
                    'location'      => $location,
                    'monthly_rent'  => $rent,
                    'bedrooms'      => $beds,
                    'bathrooms'     => $baths,
                    'property_type' => $type,
                    'status'        => $status,
                ]
            );

            // Matching registration:
            // pending properties → pending registration (visible in admin queue)
            // everything else    → approved registration 
            $regStatus  = $status === Property::STATUS_PENDING
                ? PropertyRegistration::STATUS_PENDING
                : PropertyRegistration::STATUS_APPROVED;

            $submittedAt = now()->subDays(mt_rand(10, 60));

            PropertyRegistration::updateOrCreate(
                ['property_id' => $property->id, 'submitted_by' => $owner->user_id],
                [
                    'reviewed_by'      => $regStatus === PropertyRegistration::STATUS_APPROVED
                        ? $admin->id
                        : null,
                    'status'           => $regStatus,
                    'submitted_at'     => $submittedAt,
                    'reviewed_at'      => $regStatus === PropertyRegistration::STATUS_APPROVED
                        ? $submittedAt->copy()->addDays(mt_rand(1, 5))
                        : null,
                    'rejection_reason' => null,
                ]
            );
        }
    }
}