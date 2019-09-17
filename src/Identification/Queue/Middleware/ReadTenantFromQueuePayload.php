<?php

namespace Tenancy\Identification\Drivers\Queue\Middleware;

use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Identification\Drivers\Queue\Events\Processing;

class ReadTenantFromQueuePayload
{
    public function __invoke(JobProcessing $event)
    {
        event(new Processing($event));
    }
}
