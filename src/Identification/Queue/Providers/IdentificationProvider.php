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
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Identification\Drivers\Queue\Providers;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\ResolvesTenants;

class IdentificationProvider extends ServiceProvider
{
    public function register()
    {
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
                /** @var array $payload */
                $payload = $event->job->payload();
                if ($command = Arr::get($payload, 'data.command')) {
                    $command = unserialize($command);
                }

                $key = $command->tenant_key ?? $payload['tenant_key'] ?? null;
                $identifier = $command->tenant_identifier ?? $payload['tenant_identifier'] ?? null;

                if ($key && $identifier) {
                    /** @var Environment $environment */
                    $environment = resolve(Environment::class);
                    /** @var ResolvesTenants $resolver */
                    $resolver = resolve(ResolvesTenants::class);

                    $tenant = $resolver->findModel($identifier, $key);

                    $environment->setTenant($tenant);
                }
            });

            return $queue;
        });
    }
}
