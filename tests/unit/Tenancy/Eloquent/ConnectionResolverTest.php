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

namespace Tenancy\Tests\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Tenancy\Eloquent\ConnectionResolver;
use Tenancy\Tests\Mocks\Tenant;
use Tenancy\Tests\TestCase;

class ConnectionResolverTest extends TestCase
{
    /**
     * @test
     */
    public function sets_custom_resolver_on_model()
    {
        $this->assertInstanceOf(ConnectionResolver::class, Model::getConnectionResolver());
    }

    /**
     * @test
     */
    public function overrides_global_db()
    {
        $this->assertInstanceOf(ConnectionResolver::class, $this->app['db']);
    }
    /**
     * @test
     */
    public function resolves_tenant_model_connection_when_defaulted()
    {
        config(['tenancy.database.models-default-to-tenant-connection' => true]);

        $this->assertEquals(
            config('tenancy.database.tenant-connection-name'),
            factory(Tenant::class)->make()->getConnection()->getName()
        );
    }

    /**
     * @test
     */
    public function resolves_tenant_db_connection_when_defaulted()
    {
        config(['tenancy.database.models-default-to-tenant-connection' => true]);

        $this->assertEquals(
            config('tenancy.database.tenant-connection-name'),
            $this->app['db']->getDefaultConnection()
        );
    }
}
