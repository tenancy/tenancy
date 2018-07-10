<?php

namespace Tenancy\Identification\Drivers\Environment\Contracts;

use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByEnvironment
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @return Tenant
     */
    public function tenantIdentificationByEnvironment(): ?Tenant;
}
