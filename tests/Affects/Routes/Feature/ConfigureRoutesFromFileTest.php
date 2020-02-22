<?php

namespace Tenancy\Tests\Affects\Routes\Feature;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use Tenancy\Affects\Routes\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;

class ConfigureRoutesFromFileTest extends AffectsFeatureTestCase
{
    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureRoutes::class, function (ConfigureRoutes $event) {
            $event->fromFile([], __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .  'routes.php');
        });
    }

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        return $router->has('bar');
    }
}