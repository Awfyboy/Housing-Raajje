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
                'address' => 'Henveiru District, Boduthakurufaanu Magu, 20026, Malé',
            ],
            [
                'name' => 'Hulhumalé Branch',
                'location' => BranchLocations::HULHUMALE,
                'address' => 'Phase 1, Medhu Goalhi, 23000, Hulhumalé',
            ],
            [
                'name' => 'Villingili Branch',
                'location' => BranchLocations::VILLINGILI,
                'address' => 'Ameer Ahmed Magu, Goalhi 5, 21000, Villingili',
            ],
        ];

        foreach ($branches as $branchData) {
            // Use 'name' as the unique identifier
            Branch::updateOrCreate(
                ['name' => $branchData['name']],
                $branchData
            );
        }
    }
}