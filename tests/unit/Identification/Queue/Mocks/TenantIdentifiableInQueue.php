<?php

namespace Tenancy\Tests\Identification\Queue\Mocks;

use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Testing\Mocks\Tenant as Mock;

class TenantIdentifiableInQueue extends Mock implements IdentifiesByQueue
{

    /**
     * Specify whether the tenant model is matching the queue job.
     *
     * @param JobProcessing $event
     *
     * @return Tenant
     */
    public function tenantIdentificationByQueue(JobProcessing $event): ?Tenant
    {
        dump(__CLASS__, $event);
        return $this->newQuery()->first();
    }
}
