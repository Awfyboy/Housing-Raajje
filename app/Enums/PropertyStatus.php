<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case PENDING = 'pending';              // Before staff approval
    case AVAILABLE = 'available';          // Ready for viewings
    case RENTED = 'rented';                // Has active lease
    case MAINTENANCE = 'under_maintenance'; // WIP
    

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::AVAILABLE => 'Available',
            self::RENTED => 'Rented',
            self::MAINTENANCE => 'Under Maintenance',
        };
    }
}