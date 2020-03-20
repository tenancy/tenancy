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

namespace Tenancy\Tests\Framework\Feature\Identification;

use Tenancy\Identification\Events\Identified;
use Tenancy\Identification\Events\NothingIdentified;
use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Events\Resolving;
use Tenancy\Identification\Events\Switched;
use Tenancy\Testing\TestCase;

class TenantIdentificationEventsTest extends TestCase
{
    /** @test */
    public function empty_tenant_is_forced()
    {
        $switched = $resolving = $resolved = $nothingIdentified = $identified = 0;

        $this->expectEvent(Switched::class, $switched);
        $this->expectEvent(Resolving::class, $resolving);
        $this->expectEvent(Resolved::class, $resolved);
        $this->expectEvent(NothingIdentified::class, $nothingIdentified);
        $this->expectEvent(Identified::class, $identified);

        $this->environment->setTenant();

        $this->assertEquals(1, $switched);
        $this->assertEquals(0, $resolving);
        $this->assertEquals(0, $resolved);
        $this->assertEquals(0, $nothingIdentified);
        $this->assertEquals(0, $identified);
    }

    /** @test */
    public function empty_tenant_is_resolved()
    {
        $switched = $resolving = $resolved = $nothingIdentified = $identified = 0;

        $this->expectEvent(Switched::class, $switched);
        $this->expectEvent(Resolving::class, $resolving);
        $this->expectEvent(Resolved::class, $resolved);
        $this->expectEvent(NothingIdentified::class, $nothingIdentified);
        $this->expectEvent(Identified::class, $identified);

        $this->environment->identifyTenant();

        $this->assertEquals(1, $switched);
        $this->assertEquals(1, $resolving);
        $this->assertEquals(1, $resolved);
        $this->assertEquals(1, $nothingIdentified);
        $this->assertEquals(0, $identified);
    }

    /** @test */
    public function tenant_is_resolved()
    {
        $this->resolveTenant($this->mockTenant());

        $switched = $resolving = $resolved = $nothingIdentified = $identified = 0;

        $this->expectEvent(Switched::class, $switched);
        $this->expectEvent(Resolving::class, $resolving);
        $this->expectEvent(Resolved::class, $resolved);
        $this->expectEvent(NothingIdentified::class, $nothingIdentified);
        $this->expectEvent(Identified::class, $identified);

        $this->environment->identifyTenant();

        $this->assertEquals(1, $switched);
        $this->assertEquals(0, $resolving);
        $this->assertEquals(1, $resolved);
        $this->assertEquals(0, $nothingIdentified);
        $this->assertEquals(1, $identified);
    }

    /** @test */
    public function refreshing_events()
    {
        $this->resolveTenant($this->mockTenant());

        $switched = $resolving = $resolved = $nothingIdentified = $identified = 0;

        $this->expectEvent(Switched::class, $switched);
        $this->expectEvent(Resolving::class, $resolving);
        $this->expectEvent(Resolved::class, $resolved);
        $this->expectEvent(NothingIdentified::class, $nothingIdentified);
        $this->expectEvent(Identified::class, $identified);

        $this->environment->identifyTenant();
        $this->environment->identifyTenant();

        $this->assertEquals(1, $switched);
        $this->assertEquals(0, $resolving);
        $this->assertEquals(1, $resolved);
        $this->assertEquals(0, $nothingIdentified);
        $this->assertEquals(1, $identified);

        $this->resolveTenant($this->mockTenant());
        $this->environment->identifyTenant(true);

        $this->assertEquals(2, $switched);
        $this->assertEquals(0, $resolving);
        $this->assertEquals(2, $resolved);
        $this->assertEquals(0, $nothingIdentified);
        $this->assertEquals(2, $identified);
    }

    protected function expectEvent(string $event, int &$count)
    {
        $this->events->listen($event, function () use (&$count) {
            $count++;
        });
    }
}
