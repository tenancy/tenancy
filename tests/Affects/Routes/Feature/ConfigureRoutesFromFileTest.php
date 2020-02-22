<?php

namespace Tenancy\Tests\Affects\Routes\Feature;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\Routes\AddsFromFile;

class ConfigureRoutesFromFileTest extends AffectsFeatureTestCase
{
    use AddsFromFile;

    protected $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        return $router->has('test');
    }
}
