<?php

namespace Tenancy\Tests\Mocks\Tenants;

use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Testing\Mocks\Tenant;

class NullEnvironmentTenant extends Tenant implements IdentifiesByEnvironment
{
    public function tenantIdentificationByEnvironment(): ?TenantContract
    {
        return null;
    }
}
