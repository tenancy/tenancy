<?php

namespace Tenancy\Identification\Drivers\Queue\Contracts;

use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByQueue
{

    /**
     * Specify whether the tenant model is matching the queue job.
     *
     * @param JobProcessing $event
     *
     * @return Tenant
     */
    public function tenantIdentificationByQueue(JobProcessing $event): ?Tenant;
}
