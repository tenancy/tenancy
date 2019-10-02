<?php

namespace Tenancy\Identification\Drivers\Queue\Contracts;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Events\Processing;

interface IdentifiesByQueue
{

    /**
     * Specify whether the tenant model is matching the queue job.
     *
     * @param Processing $event
     *
     * @return Tenant
     */
    public function tenantIdentificationByQueue(Processing $event = null): ?Tenant;
}
