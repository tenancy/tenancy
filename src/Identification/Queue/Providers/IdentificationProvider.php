<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Identification\Drivers\Queue\Providers;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;
use Tenancy\Environment;

class IdentificationProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['queue']->createPayloadUsing(function () {
            /** @var Environment $environment */
            $environment = $this->app->make(Environment::class);
            $tenant = $environment->getTenant();

            return $tenant ? [
                'tenant_id' => $tenant->getTenantKey(),
                'tenant_class' => \get_class($tenant)
            ] : [];
        });

        $this->app['events']->listen(JobProcessing::class, function ($event) {
            /** @var array $payload */
            $payload = $event->job->payload;

            if (isset($payload['tenant_id'], $payload['tenant_class'])) {
                /** @var Environment $environment */
                $environment = $this->app->make(Environment::class);

                $tenant = $this->app->call([$payload['tenant_class'], $payload['tenant_id']]);

                $environment->setTenant($tenant);
            }
        });
    }
}
