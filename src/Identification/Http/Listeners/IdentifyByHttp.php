<?php

namespace Tenancy\Identification\Drivers\Http\Listeners;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Events\Resolving;

class IdentifyByHttp
{
    public function handle(Resolving $event): ?Tenant
    {
        $models = $event->models->filterByContract(IdentifiesByHttp::class);

        if ($models->isEmpty()) {
            return null;
        }

        return $models
            ->map(function ($tenant) {
                return app()->call("$tenant@tenantIdentificationByHttp");
            })
            ->filter()
            ->first();
    }
}
