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

use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Events\Processing;
use Tenancy\Testing\Mocks\Tenant;

class FirstMixedTenant extends Tenant implements IdentifiesByHttp, IdentifiesByConsole, IdentifiesByQueue, IdentifiesByEnvironment
{
    public function tenantIdentificationByHttp(Request $request): ?TenantContract
    {
        event('mock.tenant.identification.http', [
            'tenant' => $this,
        ]);

        return $this
            ->newQuery()
            ->first();
    }

    public function tenantIdentificationByQueue(Processing $event): ?TenantContract
    {
        event('mock.tenant.identification.queue', [
            'tenant' => $this,
        ]);

        return $this
            ->newQuery()
            ->first();
    }

    public function tenantIdentificationByConsole(InputInterface $input): ?TenantContract
    {
        event('mock.tenant.identification.console', [
            'tenant' => $this,
        ]);

        return $this
            ->newQuery()
            ->first();
    }

    public function tenantIdentificationByEnvironment(): ?TenantContract
    {
        event('mock.tenant.identification.environment', [
            'tenant' => $this,
        ]);

        return $this
            ->newQuery()
            ->first();
    }
}
