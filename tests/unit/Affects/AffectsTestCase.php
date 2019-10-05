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

namespace Tenancy\Tests\Affects;

use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
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

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    abstract protected function assertAffected(Tenant $tenant);

    abstract protected function assertNotAffected();

    abstract protected function registerAffecting();

    abstract protected function registerForwardingCall();

    /**
     * @test
     */
    public function not_affected_by_default()
    {
        $this->registerAffecting();
        $this->assertNotAffected();
    }

    /**
     * @test
     */
    public function can_affect_the_application()
    {
        $this->registerAffecting();
        $this->assertNotAffected();

        Tenancy::setTenant($this->tenant);

        $this->assertAffected($this->tenant);
    }

    /**
     * @test
     */
    public function can_override_previous_affect()
    {
        $this->registerAffecting();
        Tenancy::setTenant($this->tenant);

        $this->assertAffected($this->tenant);

        $newTenant = $this->mockTenant();
        Tenancy::setTenant($newTenant);
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
        $this->assertNotAffected();

        Tenancy::setTenant($this->tenant);

        $this->assertAffected($this->tenant);
    }
}
