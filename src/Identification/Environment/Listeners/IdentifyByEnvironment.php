<?php

namespace Tenancy\Identification\Drivers\Environment\Listeners;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Identification\Events\Resolving;

class IdentifyByEnvironment
{
    public function handle(Resolving $event): ?Tenant
    {
        $models = $event->models->filterByContract(IdentifiesByEnvironment::class);

        if ($models->isEmpty()) {
            return null;
        }

        return $models
            ->map(function ($tenant) {
                return app()->call("$tenant@tenantIdentificationByEnvironment");
            })
            ->filter()
            ->first();
    }
}
