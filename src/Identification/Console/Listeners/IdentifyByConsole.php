<?php

namespace Tenancy\Identification\Drivers\Console\Listeners;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Events\Resolving;

class IdentifyByConsole
{
    public function handle(Resolving $event): ?Tenant
    {
        $models = $event->models->filterByContract(IdentifiesByConsole::class);

        if ($models->isEmpty()) {
            return null;
        }

        return $models
            ->map(function ($tenant) {
                return app()->call("$tenant@tenantIdentificationByConsole");
            })
            ->filter()
            ->first();
    }
}
