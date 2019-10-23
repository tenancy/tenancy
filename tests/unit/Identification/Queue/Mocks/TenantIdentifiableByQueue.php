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

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Events\Processing;
use Tenancy\Testing\Mocks\Tenant as Mock;

class TenantIdentifiableByQueue extends Mock implements IdentifiesByQueue
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
        if ($event->tenant && $event->tenant instanceof $this) {
            return $event->tenant;
        }

        if ($event->tenant_key && $event->tenant_identifier === $this->getTenantIdentifier()) {
            return $this->newQuery()
                ->where($this->getTenantKeyName(), $event->tenant_key)
                ->first();
        }

        return null;
    }
}
