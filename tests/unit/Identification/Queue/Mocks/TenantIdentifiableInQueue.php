<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Identification\Queue\Mocks;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Arr;
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
        $payload = $event->job->payload();
        if ($command = Arr::get($payload, 'data.command')) {
            $command = unserialize($command);
        }

        $key = $command->tenant_key ?? $payload['tenant_key'] ?? null;

        return $this->newQuery()->find($key);
    }
}
