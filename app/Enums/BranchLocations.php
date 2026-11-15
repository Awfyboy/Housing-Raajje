<?php

namespace App\Enums;

enum BranchLocations: string
{
    case MALE = 'male';
    case VILLINGILI = 'villingili';
    case HULHUMALE = 'hulhumale';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Malé',
            self::VILLINGILI => 'Villingili',
            self::HULHUMALE => 'Hulhumalé',
        };
    }
}
