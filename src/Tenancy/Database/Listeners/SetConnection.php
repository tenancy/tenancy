<?php

namespace Tenancy\Database\Listeners;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Arr;
use Tenancy\Database\Events\Resolved;
use Tenancy\Facades\Tenancy;

class SetConnection
{
    public function handle(Resolved $event)
    {
        $connection = $event->connection ?? Tenancy::getTenantConnectionName();
        $existingConfig = config('database.connections.' . $connection);

        $configuration = $event->provider->configure($event->tenant);

        $configuration['tenant-key'] = optional($event->tenant)->getTenantKey();
        $configuration['tenant-identifier'] = optional($event->tenant)->getTenantIdentifier();

        config(['database.connections.' . $connection  => $configuration]);

        if (Arr::get($existingConfig, 'tenant-key') !== $configuration['tenant-key'] ||
            Arr::get($existingConfig, 'tenant-identifier') !== $configuration['tenant-identifier']) {
            resolve(DatabaseManager::class)->purge($connection);
        }
    }
}