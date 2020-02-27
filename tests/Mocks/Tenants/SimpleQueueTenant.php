<?php

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
