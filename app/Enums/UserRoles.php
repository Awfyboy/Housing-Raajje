<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case BRANCH_MANAGER = 'branch_manager';
    case STAFF = 'staff';
    case RECEPTIONIST = 'receptionist';
    case PROPERTY_OWNER = 'property_owner';
    case CLIENT = 'client';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::BRANCH_MANAGER => 'Branch Manager',
            self::STAFF => 'Staff',
            self::RECEPTIONIST => 'Receptionist',
            self::PROPERTY_OWNER => 'Property Owner',
            self::CLIENT => 'Client',
        };
    }
}
