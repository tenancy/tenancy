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

namespace Tenancy\Tests\Affects\Routes;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use Tenancy\Affects\Routes\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsTestCase;

class ConfiguresRoutesNewTest extends AffectsTestCase
{
    /**
     * @var bool
     */
    protected $forwardCallTest = false;

    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureRoutes::class, function (ConfigureRoutes $event) {
            $event->fromFile([], __DIR__.'/routes.php');
        });
    }

    protected function assertAffected(Tenant $tenant)
    {
        $router = $this->app->make(Router::class);
        $this->assertTrue($router->has('bar'));
    }

    protected function assertNotAffected(Tenant $tenant)
    {
        $router = $this->app->make(Router::class);
        $this->assertFalse($router->has('bar'));
    }
}
