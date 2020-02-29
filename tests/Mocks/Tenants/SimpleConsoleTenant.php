<?php

namespace Tenancy\Tests\Mocks\Tenants;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Testing\Mocks\Tenant;

class SimpleConsoleTenant extends Tenant implements IdentifiesByConsole
{
    public function tenantIdentificationByConsole(InputInterface $input): ?TenantContract
    {
        if($input->hasParameterOption('--tenant')){
            return $this->newQuery()
                ->where('name', $input->getParameterOption('--tenant'))
                ->first();
        }

        return null;
    }
}
