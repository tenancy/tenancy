<?php

namespace Tenancy\Tests\Mocks\Tenants;

use Tenancy\Hooks\Hostname\Contracts\HasHostnames;
use Tenancy\Testing\Mocks\Tenant;

class HostnameTenant extends Tenant implements HasHostnames
{
    public function getHostnames(): array
    {
        return [$this->email];
    }
}
