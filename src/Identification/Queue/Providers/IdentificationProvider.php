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

namespace Tenancy\Identification\Drivers\Queue\Providers;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\QueueManager;
use Tenancy\Environment;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Support\DriverProvider;

class IdentificationProvider extends DriverProvider
{
    protected $drivers = [
        IdentifiesByQueue::class,
    ];

    public function register()
    {
        parent::register();

        $this->app->extend('queue', function (QueueManager $queue) {
            $queue->createPayloadUsing(function (string $connection, string $queue = null, array $payload = []) {
                if (isset($payload['tenant_key'], $payload['tenant_identifier'])) {
                    return [];
                }

                /** @var Environment $environment */
                $environment = resolve(Environment::class);
                $tenant = $environment->getTenant();

                return $tenant ? [
                    'tenant_key'        => $tenant->getTenantKey(),
                    'tenant_identifier' => $tenant->getTenantIdentifier(),
                ] : [];
            });

            $queue->before(function (JobProcessing $event) {
                $this->app->when(JobProcessing::class)
                    ->needs('$connectionName')
                    ->give($event->connectionName);
                $this->app->when(JobProcessing::class)
                    ->needs('$job')
                    ->give($event->job);

                $environment = resolve(Environment::class);
                $environment->getTenant(true);
            });

            return $queue;
        });
    }
}
