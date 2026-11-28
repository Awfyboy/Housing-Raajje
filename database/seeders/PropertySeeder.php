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
        $admin = User::where('email', 'admin1@housing4raajje.mv')->first();
        $owners = Owner::all();

        if ($owners->isEmpty() || !$admin) {
            throw new \RuntimeException('Run DatabaseSeeder first.');
        }

        $branches = Branch::pluck('id', 'name');

        /*
        |--------------------------------------------------------------------------
        |   Guaranteed statuses for owners (owner1, owner2, owner3)
        |--------------------------------------------------------------------------
        | 
        */
        //initial owner gets exactly 4 properties, one with each status.
        $test_owners = [
            'owner1@housing4raajje.mv' => [
                ['Malé', 'Henveiru', '3-Bedroom Apartment', 18000, 3, 2, 'apartment', Property::STATUS_AVAILABLE],
                ['Malé', 'Galolhu', '2-Bedroom Flat', 12500, 2, 1, 'flat', Property::STATUS_RENTED],
                ['Hulhumalé', 'Phase 1', '3-Bedroom Apartment', 20000, 3, 2, 'apartment', Property::STATUS_PENDING],
                ['Villingili', 'Goalhi 5', '2-Bedroom Apartment', 11000, 2, 1, 'apartment', Property::STATUS_UNAVAILABLE],
            ],
            'owner2@housing4raajje.mv' => [
                ['Hulhumalé', 'Phase 2', '4-Bedroom House', 28000, 4, 3, 'house', Property::STATUS_AVAILABLE],
                ['Malé', 'Maafannu', 'Studio Apartment', 9500, 1, 1, 'studio', Property::STATUS_RENTED],
                ['Villingili', 'Ameer Ahmed', '3-Bedroom House', 22000, 3, 2, 'house', Property::STATUS_PENDING],
                ['Hulhumalé', 'Phase 1', '2-Bedroom Flat', 15000, 2, 1, 'flat', Property::STATUS_UNAVAILABLE],
            ],
            'owner3@housing4raajje.mv' => [
                ['Villingili', 'Kanbulo', '4-Bedroom House', 25000, 4, 3, 'house', Property::STATUS_AVAILABLE],
                ['Malé', 'Machchangolhi', '2-Bedroom Apartment', 13500, 2, 1, 'apartment', Property::STATUS_RENTED],
                ['Hulhumalé', 'Phase 2', '3-Bedroom Apartment', 21000, 3, 2, 'apartment', Property::STATUS_PENDING],
                ['Malé', 'Henveiru', '1-Bedroom Flat', 8500, 1, 1, 'flat', Property::STATUS_UNAVAILABLE],
            ],
        ];

        foreach ($test_owners as $email => $properties) {
            $owner = Owner::whereHas('user', fn ($q) => $q->where('email', $email))->first();
            if (!$owner) continue;

            foreach ($properties as [$location, $district, $title, $rent, $beds, $baths, $type, $status]) {
                $branchName = match ($location) {
                    'Malé' => 'Malé Branch',
                    'Hulhumalé' => 'Hulhumalé Branch',
                    'Villingili' => 'Villingili Branch',
                };

                $property = Property::create([
                    'owner_id'      => $owner->id,
                    'branch_id'     => $branches[$branchName] ?? null,
                    'title'         => $title,
                    'description'   => "Property owned by {$owner->user->name}.",
                    'address'       => "{$location}, {$district}",
                    'location'      => $location,
                    'monthly_rent'  => $rent,
                    'bedrooms'      => $beds,
                    'bathrooms'     => $baths,
                    'property_type' => $type,
                    'status'        => $status,
                ]);

                $this->createRegistration($property, $owner, $admin);
            }
        }

        /*
        |--------------------------------------------------------------------------
        |   Generate remaining 108 properties via factory
        |--------------------------------------------------------------------------
        |
        */
        $remaining = 108; //leftover apartments spared from initial owners

        $statuses = array_merge(
            array_fill(0, 54, Property::STATUS_AVAILABLE),   // ~50%
            array_fill(0, 27, Property::STATUS_RENTED),      // ~25%
            array_fill(0, 14, Property::STATUS_PENDING),     // ~13%
            array_fill(0, 13, Property::STATUS_UNAVAILABLE)  // ~12%
        );
        shuffle($statuses);

        for ($i = 0; $i < $remaining; $i++) {
            $owner = $owners->random();

            $property = Property::factory()->make([
                'owner_id' => $owner->id,
                'status'   => $statuses[$i],
            ]);
            $property->save();

            $this->createRegistration($property, $owner, $admin);
        }
    }

    private function createRegistration(Property $property, Owner $owner, User $admin): void
    {
        $regStatus = $property->status === Property::STATUS_PENDING
            ? PropertyRegistration::STATUS_PENDING
            : PropertyRegistration::STATUS_APPROVED;

        $submittedAt = now()->subDays(mt_rand(10, 60));

        PropertyRegistration::create([
            'property_id' => $property->id,
            'submitted_by' => $owner->user_id,
            'reviewed_by' => $regStatus === PropertyRegistration::STATUS_APPROVED ? $admin->id : null,
            'status' => $regStatus,
            'submitted_at' => $submittedAt,
            'reviewed_at' => $regStatus === PropertyRegistration::STATUS_APPROVED ? $submittedAt->copy()->addDays(mt_rand(1, 5)) : null,
        ]);
    }
}