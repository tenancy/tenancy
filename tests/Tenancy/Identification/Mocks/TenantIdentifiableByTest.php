<?php

namespace Tenancy\Tests\Framework\Identification\Mocks;

use Tenancy\Testing\Mocks\Tenant;

class TenantIdentifiableByTest extends Tenant implements IdentifiesByTest
{
    public function tenantIdentificationByTest(): ?\Tenancy\Identification\Contracts\Tenant
    {
        return $this->newQuery()->first();
    }
}
