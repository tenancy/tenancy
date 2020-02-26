<?php

namespace Tenancy\Tests\Mocks\Tenants;

use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Events\Processing;
use Tenancy\Testing\Mocks\Tenant;

class NullQueueTenant extends Tenant implements IdentifiesByQueue
{
    public function tenantIdentificationByQueue(Processing $event): ?TenantContract
    {
        return null;
    }
}
