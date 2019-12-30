<?php

namespace Tenancy\Tests\Framework\Identification\Mocks;

use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByTest{
    public function tenantIdentificationByTest(): ?Tenant;
}
