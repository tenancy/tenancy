<?php declare(strict_types=1);

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

namespace Tenancy\Tests\Facades;

use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Events\Resolving;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class TenancyTest extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $this->tenant = factory(Tenant::class)->make();

        $resolver->addModel(Tenant::class);
    }

    /**
     * @test
     */
    public function can_proxy_environment_calls()
    {
        $this->assertNull(Tenancy::getTenant());

        $this->assertInstanceOf(Environment::class, Tenancy::setTenant($this->tenant));

        $this->assertEquals($this->tenant->name, optional(Tenancy::getTenant())->name);
    }

    /**
     * @test
     * @covers \Tenancy\Environment::setIdentified
     */
    public function setting_identified_ignores_auto_identification()
    {
        $this->events->listen(Resolving::class, function (Resolving $event) {
            return $this->tenant;
        });

        Tenancy::setIdentified(true);

        $this->assertNull(Tenancy::getTenant());

        Tenancy::setIdentified(false);

        $this->assertNotNull(Tenancy::getTenant());
    }

    /**
     * @test
     * @covers \Tenancy\Environment::getTenant
     */
    public function refreshing_loads_new_tenant()
    {
        $this->assertNull(Tenancy::getTenant());

        $this->events->listen(Resolving::class, function (Resolving $event) {
            return $this->tenant;
        });

        $this->assertNull(Tenancy::getTenant());
        $this->assertNotNull(Tenancy::getTenant(true));
    }

    /**
     * @test
     * @covers \Tenancy\Environment::getSystemConnection
     * @covers \Tenancy\Environment::getDefaultSystemConnectionName
     */
    public function can_retrieve_system_connection()
    {
        $this->assertEquals(
            Environment::getDefaultSystemConnectionName(),
            Tenancy::getSystemConnection()->getName()
        );
    }

    /**
     * @test
     * @covers \Tenancy\Environment::getTenantConnection
     */
    public function can_retrieve_tenant_connection()
    {
        $this->assertEquals(
            config('tenancy.database.tenant-connection-name'),
            Tenancy::getTenantConnection()->getName()
        );
    }
}
