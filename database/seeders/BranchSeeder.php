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
                'name' => 'Malé Central Branch',
                'location' => BranchLocations::MALE,
                'address' => 'Henveiru District, Boduthakurufaanu Magu, 20026',
            ],
            [
                'name' => 'Malé West Branch',
                'location' => BranchLocations::MALE,
                'address' => 'Maafannu District, Chaandhanee Magu, 20063',
            ],
            [
                'name' => 'Hulhumalé Phase 1 Branch',
                'location' => BranchLocations::HULHUMALE,
                'address' => 'Phase 1, Medhu Goalhi, Lot 11122, 23000',
            ],
            [
                'name' => 'Hulhumalé Phase 2 Branch',
                'location' => BranchLocations::HULHUMALE,
                'address' => 'Phase 2, Neeloafaru Magu, Lot 11483, 21000',
            ],
            [
                'name' => 'Villingili Branch',
                'location' => BranchLocations::VILLINGILI,
                'address' => 'Ameer Ahmed Magu, Goalhi 5, 21000',
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