<?php

namespace Tenancy\Tests\Identification\Drivers\Environment\Mocks;

use Tenancy\Identification\Contracts\Tenant as Contract;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;

class Tenant extends \Tenancy\Tests\Mocks\Tenant implements IdentifiesByEnvironment
{

    /**
     * Specify whether the tenant model is matching the request.
     *
     * @return Contract
     */
    public function tenantIdentificationByEnvironment(): ?Contract
    {
        return $this->newQuery()
            ->where('name', env('TENANT_NAME'))
            ->first();
    }
}
