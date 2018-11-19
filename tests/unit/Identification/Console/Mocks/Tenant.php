<?php

namespace Tenancy\Tests\Identification\Drivers\Console\Mocks;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant as Contract;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;

class Tenant extends \Tenancy\Testing\Mocks\Tenant implements IdentifiesByConsole
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param InputInterface $input
     * @return null|Contract
     */
    public function tenantIdentificationByConsole(InputInterface $input): ?Contract
    {
        return $input->hasParameterOption('--tenant')
            ? $this->newQuery()
                ->where('name', $input->getParameterOption('--tenant'))
                ->first()
            : null;
    }
}
