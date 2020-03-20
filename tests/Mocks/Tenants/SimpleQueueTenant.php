<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Mocks\Tenants;

use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Events\Processing;
use Tenancy\Testing\Mocks\Tenant;

class SimpleQueueTenant extends Tenant implements IdentifiesByQueue
{
    public function tenantIdentificationByQueue(Processing $event): ?TenantContract
    {
        if ($event->tenant) {
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
