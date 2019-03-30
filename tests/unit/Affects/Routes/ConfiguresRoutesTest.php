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

namespace Tenancy\Tests\Affects\Routes;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use Tenancy\Affects\Routes\Providers\ServiceProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresRoutesTest extends TestCase
{
    protected $additionalProviders = [ServiceProvider::class];
    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function flushes_existing_routes()
    {
        $this->events->listen(ConfigureRoutes::class, function (ConfigureRoutes $event) {
            $event->flush();
        });

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();

        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $this->assertEquals(0, $router->getRoutes()->count());
    }

    /**
     * @test
     */
    public function reads_routes_file()
    {
        $this->events->listen(ConfigureRoutes::class, function (ConfigureRoutes $event) {
            $event->fromFile([], __DIR__ . '/routes.php');
        });

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();

        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $this->assertTrue($router->has('bar'));
    }
}
