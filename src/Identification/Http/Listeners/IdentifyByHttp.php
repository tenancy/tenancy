<?php

namespace Tenancy\Identification\Drivers\Http\Listeners;

use Illuminate\Contracts\Foundation\Application;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Events\Resolving;

class IdentifyByHttp
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Resolving $event): ?Tenant
    {
        $models = $event->models->filterByContract(IdentifiesByHttp::class);

        if ($models->isEmpty()) {
            return;
        }

        return $models->first(function ($tenant) {
            return $this->app->call("$tenant@tenantIdentificationByHttp");
        });
    }
}
