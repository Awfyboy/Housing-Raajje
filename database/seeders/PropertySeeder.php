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
        $branches = Branch::pluck('id', 'name');
        $admin = User::where('email', 'admin1@housing4raajje.mv')->first();

        // Fetch all owners 
        $owners = Owner::all();

        if ($owners->isEmpty() || !$admin) {
            throw new \RuntimeException('Run DatabaseSeeder first.');
        }

        // Distribution: 60 Available, 30 Rented, 15 Pending, 15 Unavailable
        $statuses = array_merge(
            array_fill(0, 60, Property::STATUS_AVAILABLE),
            array_fill(0, 30, Property::STATUS_RENTED),
            array_fill(0, 15, Property::STATUS_PENDING),
            array_fill(0, 15, Property::STATUS_UNAVAILABLE)
        );
        shuffle($statuses);

        for ($i = 0; $i < 120; $i++) {
            // Pick a random owner (could be a test one or a generated one)
            $owner = $owners->random();
            
            // Factory handles the Maldivian address + details
            $property = Property::factory()->make([
                'owner_id' => $owner->id,
                'status' => $statuses[$i],
            ]);

            // Auto-match the branch
            $branchName = match ($property->location) {
                'Malé' => 'Malé Branch',
                'Hulhumalé' => 'Hulhumalé Branch',
                'Villingili' => 'Villingili Branch',
            };
            $property->branch_id = $branches[$branchName] ?? $branches->first();
            $property->save();

            // Registration
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
}