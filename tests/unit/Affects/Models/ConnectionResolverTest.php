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

namespace Tenancy\Tests\Affects\Models;

use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Affects\Models\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConnectionResolverTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @test
     */
    public function sets_default_connection()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->onTenant([Tenant::class], false);
        });

        $this->resolveTenant($this->mockTenant());
        Tenancy::getTenant();

        (new Tenant())->getConnectionResolver()->setDefaultConnection("tenant2");
        $this->assertEquals(
            "tenant2",
            (new Tenant())->getConnectionResolver()->getDefaultConnection()
        );
    }

    /**
     * @test
     */
    public function mirrors_db_calls()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->onTenant([Tenant::class], false);
        });

        $this->resolveTenant($this->mockTenant());
        Tenancy::getTenant();


        $resolver = (new Tenant())->getConnectionResolver();
        $this->assertIsArray(
            $resolver->supportedDrivers()
        );
    }
}
