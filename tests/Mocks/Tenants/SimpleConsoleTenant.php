<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Mocks\Tenants;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Testing\Mocks\Tenant;

class SimpleConsoleTenant extends Tenant implements IdentifiesByConsole
{
    public function tenantIdentificationByConsole(InputInterface $input): ?TenantContract
    {
        if ($input->hasParameterOption('--tenant')) {
            return $this->newQuery()
                ->where('name', $input->getParameterOption('--tenant'))
                ->first();
        }

        return null;
    }
}
