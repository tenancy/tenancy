<?php

namespace Tenancy\Tests\Identification\Queue\Mocks;

use Illuminate\Support\Arr;
use Tenancy\Testing\Mocks\Tenant as Mock;
use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;

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
        $payload = $event->job->payload();
        if ($command = Arr::get($payload, 'data.command')) {
            $command = unserialize($command);
        }

        $key = $command->tenant_key ?? $payload['tenant_key'] ?? null;

        return $this->newQuery()->find($key);
    }
}
