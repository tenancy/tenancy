<?php

namespace Tenancy\Identification\Drivers\Queue\Listeners;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Queue\Events\JobProcessing;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Events\Resolving;

class IdentifyByQueue
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Resolving $event): ?Tenant
    {
        if (! $this->app->bound(JobProcessing::class)) {
            return null;
        }

        $models = $event->models->filterByContract(IdentifiesByQueue::class);

        if ($models->isEmpty()) {
            return null;
        }

        return $models
            ->map(function ($tenant) {
                return $this->app->call("$tenant@tenantIdentificationByQueue");
            })
            ->filter()
            ->first();
    }

}
