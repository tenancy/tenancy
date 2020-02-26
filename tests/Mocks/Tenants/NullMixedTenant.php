<?php

namespace Tenancy\Tests\Mocks\Tenants;

use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Queue\Events\Processing;

class NullMixedTenant extends Tenant implements IdentifiesByHttp, IdentifiesByConsole, IdentifiesByQueue
{

    public function tenantIdentificationByHttp(Request $request): ?TenantContract
    {
        return null;
    }

    public function tenantIdentificationByQueue(Processing $event): ?TenantContract
    {
        return null;
    }

    public function tenantIdentificationByConsole(InputInterface $input): ?TenantContract
    {
        return null;
    }
}
