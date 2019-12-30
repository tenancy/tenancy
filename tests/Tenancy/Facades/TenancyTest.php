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

namespace Tenancy\Tests\Framework\Facades;

use Tenancy\Database\Events\Resolving;
use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Events\Switched;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Database\Mocks\NullDriver;

class TenancyTest extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->events->forget(Resolving::class);

        $this->events->listen(Resolving::class, function (Resolving $event) {
            return new NullDriver();
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
        $this->assertNull(Tenancy::identifyTenant());

        $this->assertInstanceOf(Environment::class, Tenancy::setTenant($this->tenant));

        $this->assertEquals($this->tenant->name, optional(Tenancy::identifyTenant())->name);
    }

    /**
     * @test
     * @covers \Tenancy\Environment::setIdentified
     */
    public function setting_identified_ignores_auto_identification()
    {
        $this->resolveTenant($this->tenant);

        Tenancy::setIdentified(true);

        $this->assertNull(Tenancy::identifyTenant());

        Tenancy::setIdentified(false);

        $this->assertNotNull(Tenancy::identifyTenant());
    }

    /**
     * @test
     * @covers \Tenancy\Environment::getTenant
     */
    public function refreshing_loads_new_tenant()
    {
        $this->assertNull(Tenancy::identifyTenant());

        $this->resolveTenant($this->tenant);

        $this->assertNull(Tenancy::getTenant());
        $this->assertNotNull(Tenancy::identifyTenant(true));
    }

    /**
     * @test
     * @covers \Tenancy\Environment::setTenant
     */
    public function can_switch_tenant()
    {
        $this->assertNull(Tenancy::identifyTenant());

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
        $switched = Tenancy::identifyTenant();

        $this->assertNotEquals($this->tenant->getTenantKey(), $switched->getTenantKey());
        $this->assertEquals($switch->getTenantKey(), $switched->getTenantKey());
    }
}
