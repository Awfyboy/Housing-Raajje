<?php

namespace Database\Seeders;

use App\Enums\BranchLocations;
use App\Models\Entity\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Malé Branch',
                'location' => BranchLocations::MALE,
                'address' => 'Malé, Maldives',
            ],
            [
                'name' => 'Hulhumalé Branch',
                'location' => BranchLocations::HULHUMALE,
                'address' => 'Hulhumalé, Maldives',
            ],
            [
                'name' => 'Villingili Branch',
                'location' => BranchLocations::VILLINGILI,
                'address' => 'Villingili, Maldives',
            ],
        ];

        foreach ($branches as $branchData) {
            Branch::updateOrCreate(
                ['location' => $branchData['location']->value],
                $branchData
            );
        }
    }
}
