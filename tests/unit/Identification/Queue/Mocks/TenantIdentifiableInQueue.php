<?php

namespace Tenancy\Tests\Identification\Queue\Mocks;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Events\Processing;
use Tenancy\Testing\Mocks\Tenant as Mock;

class TenantIdentifiableInQueue extends Mock implements IdentifiesByQueue
{

    /**
     * Specify whether the tenant model is matching the queue job.
     *
     * @param Processing $event
     *
     * @return Tenant
     */
    public function tenantIdentificationByQueue(Processing $event = null): ?Tenant
    {
        return $this->newQuery()->first();
    }
}
