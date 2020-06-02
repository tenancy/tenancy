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

namespace Tenancy\Affects\Connections\Listeners;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Arr;
use Tenancy\Affects\Connections\Events\Resolved;
use Tenancy\Facades\Tenancy;

class SetConnection
{
    public function handle(Resolved $event)
    {
        $connection = $event->connection ?? Tenancy::getTenantConnectionName();
        $existingConfig = config('database.connections.'.$connection);

        $key = optional($event->tenant)->getTenantKey();
        $identifier = optional($event->tenant)->getTenantIdentifier();

        if ($event->tenant && $event->provider) {
            $configuration = $event->provider->configure($event->tenant);

            $configuration['tenant-key'] = $key;
            $configuration['tenant-identifier'] = $identifier;

            config(['database.connections.'.$connection => $configuration]);
        } else {
            config(['database.connections.'.$connection => null]);
        }

        if (!$event->tenant
            || !$event->provider
            || Arr::get($existingConfig, 'tenant-key') !== $key
            || Arr::get($existingConfig, 'tenant-identifier') !== $identifier) {
            /** @var DatabaseManager $manager */
            $manager = resolve(DatabaseManager::class);
            optional($manager)->purge($connection);
        }
    }
}
