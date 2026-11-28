<?php

namespace Database\Factories;

use App\Models\Entity\Owner;
use App\Models\Entity\Branch;
use App\Models\Entity\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = \App\Models\Entity\Property::class;

    public function definition(): array
    {
        $location = $this->faker->randomElement(['Malé', 'Hulhumalé', 'Villingili']);

        $houseNames = [
            'Asurumaage', 'Dhumburi', 'Veyoge', 'Mirusmaage', 'Finifenmaage',
            'Aabaadhu', 'Rahvehi', 'Huvandhumaa', 'Kethi', 'Dhoovehi',
            'Kurangi', 'Villu', 'Ethere', 'Bahaaru',
            'Fashan', 'Kaani', 'Dhigga', 'Boa', 
            'Asuruge', 'Dhumbuge', 'Mirusge', 'Finifenge', 'Aabaaduge',
            'Rahvehige', 'Huvandhumaage', 'Kethige', 'Dhoovehige',
            'Kurange', 'Villuge', 'Etherege', 'Bahaaruge',
            'Fashange', 'Kaanige', 'Dhigge', 'Boage',
        ];
        $houseName = $this->faker->randomElement($houseNames);

        if ($location === 'Malé') {
            $districts = ['Henveiru', 'Galolhu', 'Machchangolhi', 'Maafannu', 'Villimale'];
            $streets = ['Boduthakurufaanu Magu', 'Majeedhee Magu', 'Chaandhanee Magu', 'Sosun Magu', 'Ameer Ahmed Magu', 'Izzuddeen Magu', 'Maldives Port Road', 'Dhilbahaaru Magu', 'Orchid Magu'];
            $postalCode = '20' . $this->faker->numberBetween(100, 999);
            $prefix = 'M.';
            $branchName = 'Malé Branch';
        } elseif ($location === 'Hulhumalé') {
            $districts = ['Phase 1', 'Phase 2'];
            $streets = ['Medhu Goalhi', 'Beach Road', 'Nirolhu Magu', 'Roashanee Magu', 'Neeloafaru Magu', 'Dhonbili Magu', 'Vashaakiri Magu', 'Bodufungadhu Magu', 'Kanbulo Magu'];
            $district = $this->faker->randomElement($districts);
            $postalCode = ($district === 'Phase 2') ? '24000' : '23000';
            $prefix = 'H.';
            $branchName = 'Hulhumalé Branch';
        } else {
            $districts = ['Ameer Ahmed', 'Goalhi 5', 'Kanbulo'];
            $streets = ['Ameer Ahmed Magu', 'Goalhi 5', 'Kanbulo Goalhi', 'Bodufungadhu Magu', 'Dhilbahaaru Magu', 'Maldives Port Road', 'Vashaakiri Magu', 'Dhonbili Magu'];
            $postalCode = '21' . $this->faker->numberBetween(100, 999);
            $prefix = 'V.';
            $branchName = 'Villingili Branch';
        }

        $street = $this->faker->randomElement($streets);
        $district = $this->faker->randomElement($districts);
        $fullAddress = "{$prefix} {$houseName}, {$street}, {$district}, {$postalCode}";
        $branchId = Branch::where('name', $branchName)->first()?->id;

        return $this->makeProperty($houseName, $fullAddress, $location, $branchId);
    }

    private function makeProperty(string $houseName, string $fullAddress, string $location, ?int $branchId): array
    {
        $propertyType = $this->faker->randomElement(['apartment', 'flat', 'studio', 'house', 'room']);
        $bedrooms = $this->faker->numberBetween(1, 4);

        $baseRent = $bedrooms * 5000;
        $rent = $baseRent + $this->faker->numberBetween(2000, 10000);

        return [
            'owner_id'      => Owner::factory(),
            'branch_id'     => $branchId,
            'title'         => "{$houseName} — {$bedrooms}-Bedroom " . ucfirst($propertyType),
            'description'   => $this->faker->paragraph(2),
            'address'       => $fullAddress,
            'location'      => $location,
            'monthly_rent'  => $rent,
            'bedrooms'      => $bedrooms,
            'bathrooms'     => $this->faker->numberBetween(1, max(1, $bedrooms - 1)),
            'property_type' => $propertyType,
            'status'        => Property::STATUS_AVAILABLE,
        ];
    }
}