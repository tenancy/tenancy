<?php

namespace Tenancy\Identification\Drivers\Console\Contracts;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByConsole
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param InputInterface $input
     * @return null|Tenant
     */
    public function tenantIdentificationByConsole(InputInterface $input): ?Tenant;
}
