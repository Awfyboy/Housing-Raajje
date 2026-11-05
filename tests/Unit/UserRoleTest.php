<?php

namespace Tests\Unit;

use App\Enums\UserRoles;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_roles_have_correct_labels()
    {
        $this->assertEquals('Admin', UserRoles::ADMIN->label());
        $this->assertEquals('Client', UserRoles::CLIENT->label());
        $this->assertEquals('Property Owner', UserRoles::PROPERTY_OWNER->label());
    }

    public function test_roles_have_correct_string_values()
    {
        $this->assertEquals('admin', UserRoles::ADMIN->value);
        $this->assertEquals('client', UserRoles::CLIENT->value);
        $this->assertEquals('property_owner', UserRoles::PROPERTY_OWNER->value);
    }
}