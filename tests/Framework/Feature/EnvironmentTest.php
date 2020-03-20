<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Framework\Feature;

use Tenancy\Testing\TestCase;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function it_returns_null_when_no_tenant_identified()
    {
        $this->assertNull($this->environment->getTenant());
    }

    /** @test */
    public function error_on_wrong_object()
    {
        $tenant = new \stdClass();

        $this->expectException(\TypeError::class);

        $this->environment->setTenant($tenant);
    }

    /** @test */
    public function prefers_identified_tenant()
    {
        $tenant = $this->mockTenant();
        $newTenant = $this->mockTenant();

        $this->resolveTenant($newTenant);
        $this->environment->setTenant($tenant);

        $this->assertEquals(
            $tenant,
            $this->environment->getTenant()
        );
    }

    /** @test */
    public function it_can_set_the_tenant()
    {
        $tenant = $this->mockTenant();

        $this->environment->setTenant($tenant);

        $this->assertEquals(
            $tenant,
            $this->environment->getTenant()
        );
    }

    /** @test */
    public function setting_identified_ignores_auto_identification()
    {
        $this->resolveTenant($this->mockTenant());

        $this->environment->setIdentified(true);

        $this->assertNull($this->environment->identifyTenant());

        $this->environment->setIdentified(false);

        $this->assertNotNull($this->environment->identifyTenant());
    }

    /** @test */
    public function refreshing_loads_new_tenant()
    {
        $this->assertNull($this->environment->identifyTenant());

        $this->resolveTenant($this->mockTenant());

        $this->assertNull($this->environment->getTenant());
        $this->assertNotNull($this->environment->identifyTenant(true));
    }
}
