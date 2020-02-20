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

abstract class AffectsTestCase extends TestCase
{
    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var bool
     */
    protected $forwardCallTest = true;

    /**
     * @var bool
     */
    protected $undoTest = true;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    abstract protected function assertAffected(Tenant $tenant);

    abstract protected function assertNotAffected(Tenant $tenant);

    abstract protected function registerAffecting();

    protected function registerForwardingCall()
    {
        //
    }

    protected function beforeIdentification(Tenant $tenant = null)
    {
        //
    }

    protected function afterIdentification(Tenant $tenant = null)
    {
        //
    }

    /**
     * @test
     */
    public function not_affected_by_default()
    {
        $this->registerAffecting();
        $this->assertNotAffected($this->tenant);
    }

    /**
     * @test
     */
    public function can_affect_the_application()
    {
        $this->registerAffecting();
        $this->identifyTenant($this->tenant);

        $this->assertAffected($this->tenant);
    }

    /**
     * @test
     */
    public function affects_can_be_undone()
    {
        if (!$this->undoTest) {
            $this->markTestSkipped();

            return;
        }

        $this->registerAffecting();
        $this->identifyTenant($this->tenant);

        $this->assertAffected($this->tenant);

        $this->identifyTenant(null);

        $this->assertNotAffected($this->tenant);
    }

    /**
     * @test
     */
    public function can_override_previous_affect()
    {
        $this->registerAffecting();
        $this->identifyTenant($this->tenant);

        $this->assertAffected($this->tenant);

        $newTenant = $this->mockTenant();
        $this->identifyTenant($newTenant);

        $this->assertAffected($newTenant);
    }

    /**
     * @test
     */
    public function can_forward_calls()
    {
        if (!$this->forwardCallTest) {
            $this->markTestSkipped();

            return;
        }

        $this->registerForwardingCall();
        $this->assertNotAffected($this->tenant);

        $this->identifyTenant($this->tenant);

        $this->assertAffected($this->tenant);
    }

    protected function identifyTenant(Tenant $tenant = null)
    {
        $this->beforeIdentification($tenant);

        Tenancy::setTenant($tenant);

        $this->afterIdentification($tenant);
    }
}
