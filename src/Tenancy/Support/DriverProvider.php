<?php

namespace Tenancy\Support;

use Illuminate\Support\ServiceProvider;
use Tenancy\Identification\Contracts\ResolvesTenants;

abstract class DriverProvider extends ServiceProvider
{
    use Concerns\PublishesConfigs;
    /**
     * Identification driver registered by the Service Provider.
     *
     * @var array
     */
    protected $drivers = [];

    public function register()
    {
        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            foreach ($this->drivers as $contract => $method) {
                $resolver->registerDriver($contract, $method);
            }
        });

        $this->publishConfigs();
    }
}