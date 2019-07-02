<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

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
        $existingConfig = config('database.connections.'.$connection);

        if ($event->tenant && $event->provider) {
            $configuration = $event->provider->configure($event->tenant);

            $configuration['tenant-key'] = optional($event->tenant)->getTenantKey();
            $configuration['tenant-identifier'] = optional($event->tenant)->getTenantIdentifier();

            config(['database.connections.'.$connection  => $configuration]);
        } else {
            config(['database.connections.'.$connection  => null]);
        }

        if (!$event->tenant
            || !$event->provider
            || Arr::get($existingConfig, 'tenant-key') !== $configuration['tenant-key']
            || Arr::get($existingConfig, 'tenant-identifier') !== $configuration['tenant-identifier']) {
            resolve(DatabaseManager::class)->purge($connection);
        }
    }
}
