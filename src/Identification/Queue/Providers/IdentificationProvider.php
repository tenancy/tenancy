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

namespace Tenancy\Identification\Drivers\Queue\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Queue\QueueManager;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Middleware;
use Tenancy\Support\DriverProvider;

class IdentificationProvider extends DriverProvider
{
    protected array $drivers = [
        IdentifiesByQueue::class,
    ];

    public function boot()
    {
        $this->app->booted(function (Application $app) {
            $app->extend('queue', function (QueueManager $queue) {
                // Store tenant key and identifier on job payload when a tenant is identified.
                $queue->createPayloadUsing($this->app->make(Middleware\SaveTenantOnQueuePayload::class));

                // Resolve any tenant related meta data on job and allow resolving of tenant.
                $queue->before($this->app->make(Middleware\ReadTenantFromQueuePayload::class));

                return $queue;
            });
        });
    }
}
