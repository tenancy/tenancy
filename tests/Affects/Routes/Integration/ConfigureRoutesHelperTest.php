<?php

namespace Tenancy\Tests\Affects\Routes\Integration;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use Tenancy\Affects\Routes\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTest;

class ConfigureRoutesHelperTest extends AffectsIntegrationTest
{
    protected $additionalProviders = [Provider::class];

    /** @test */
    public function registered_routes_are_loaded()
    {
        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            "http://localhost/foo",
            route('bar')
        );
    }

    /** @test */
    public function registered_routes_can_be_accessed()
    {
        Tenancy::setTenant($this->tenant);

        $this
            ->get(route('bar'))
            ->assertOk();
    }

    /** @test */
    public function registered_routes_have_the_right_data()
    {
        Tenancy::setTenant($this->tenant);

        $this
            ->get(route('bar'))
            ->assertSeeText($this->tenant->getTenantKey());
    }

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureRoutes::class, function (ConfigureRoutes $event) {
            $event->fromFile([], __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .  'routes.php');
        });
    }
}
