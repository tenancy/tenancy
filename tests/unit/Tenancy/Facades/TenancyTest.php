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

use Tenancy\Database\Events\Resolving;
use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Events\Switched;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use InvalidArgumentException;
use Tenancy\Tests\Database\Mocks\NullDriver;

class TenancyTest extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->events->forget(Resolving::class);

        $this->events->listen(Resolving::class, function (Resolving $event) {
            return new NullDriver;
        });

        /** @var ResolvesTenants $resolver */
        $resolver = resolve(ResolvesTenants::class);
        $this->tenant = $this->mockTenant();

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
        $this->resolveTenant($this->tenant);

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

        $this->resolveTenant($this->tenant);

        $this->assertNull(Tenancy::getTenant());
        $this->assertNotNull(Tenancy::getTenant(true));
    }

    /**
     * @test
     * @covers \Tenancy\Environment::getTenantConnection
     */
    public function can_retrieve_tenant_connection()
    {
        $this->expectException(InvalidArgumentException::class);
        Tenancy::getTenantConnection()->getName();
    }

    /**
     * @test
     * @covers \Tenancy\Environment::setTenant
     */
    public function can_switch_tenant()
    {
        $this->assertNull(Tenancy::getTenant());

        $this->resolveTenant($this->tenant);

        $this->assertEquals($this->tenant->getTenantKey(), Tenancy::getTenant(true)->getTenantKey());

        /** @var Tenant $switch */
        $switch = $this->mockTenant();

        $switchedEventFired = false;

        $this->events->listen(Switched::class, function (Switched $event) use (&$switchedEventFired, $switch) {
            $this->assertEquals($switch->getTenantKey(), $event->tenant->getTenantKey());

            $switchedEventFired = true;
        });

        Tenancy::setTenant($switch);

        $this->assertTrue($switchedEventFired);

        /** @var Tenant $switched */
        $switched = Tenancy::getTenant();

        $this->assertNotEquals($this->tenant->getTenantKey(), $switched->getTenantKey());
        $this->assertEquals($switch->getTenantKey(), $switched->getTenantKey());
    }

    /**
     * @test
     */
    public function switch_tenant_sets_connection()
    {
        $this->assertNull(config('database.connections.'. Tenancy::getTenantConnectionName()));

        Tenancy::setTenant($this->tenant);

        $this->assertEquals(
            config('database.connections.' . Tenancy::getTenantConnectionName() . '.tenant-key'),
            $this->tenant->getTenantKey()
        );
        $this->assertEquals(
            config('database.connections.' . Tenancy::getTenantConnectionName() . '.tenant-identifier'),
            $this->tenant->getTenantIdentifier()
        );
    }
}
