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

namespace Tenancy\Tests\Affects;

use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

abstract class AffectsFeatureTestCase extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    abstract protected function isAffected(Tenant $tenant): bool;

    abstract protected function registerAffecting();

    protected function beforeIdentification(Tenant $tenant = null)
    {
        //
    }

    protected function afterIdentification(Tenant $tenant = null)
    {
        //
    }

    protected function assertAffected(Tenant $tenant)
    {
        $this->assertTrue(
            $this->isAffected($tenant),
            "Application is not affected by {$tenant->getTenantKey()}"
        );
    }

    protected function assertNotAffected(Tenant $tenant)
    {
        $this->assertFalse(
            $this->isAffected($tenant),
            "Application is affected by {$tenant->getTenantKey()}"
        );
    }

    /** @test */
    public function not_affected_by_default()
    {
        $this->registerAffecting();
        $this->assertNotAffected($this->tenant);
    }

    /** @test */
    public function it_can_affect_the_application()
    {
        $this->registerAffecting();

        $this->identifyTenant($this->tenant);

        $this->assertAffected($this->tenant);
    }

    /** @test */
    public function can_override_previous_affect()
    {
        $this->registerAffecting();
        $this->identifyTenant($this->tenant);
        $this->assertAffected($this->tenant);

        $newTenant = $this->mockTenant();
        $this->identifyTenant($newTenant);

        $this->assertAffected($newTenant);
    }

    protected function identifyTenant(Tenant $tenant = null)
    {
        $this->beforeIdentification($tenant);

        Tenancy::setTenant($tenant);

        $this->afterIdentification($tenant);
    }
}
