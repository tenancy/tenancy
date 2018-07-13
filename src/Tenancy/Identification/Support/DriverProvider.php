<?php

namespace Tenancy\Identification\Support;

use Illuminate\Support\ServiceProvider;
use Tenancy\Identification\Contracts\ResolvesTenants;

abstract class DriverProvider extends ServiceProvider
{
    protected $drivers = [];

    public function register()
    {
        $this->app->booted(function ($app) {
            /** @var ResolvesTenants $resolver */
            $resolver = $app->make(ResolvesTenants::class);

            foreach ($this->drivers as $contract => $method) {
                $resolver->registerDriver($contract, $method);
            }
        });
    }
}
