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

namespace Tenancy\Tests\Framework\Unit\Hooks;

use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Hooks\SimpleHook;

class HookTest extends TestCase
{
    /** @var SimpleHook */
    private $hook;

    public function afterSetUp()
    {
        $this->hook = $this->app->make(SimpleHook::class);
    }

    /** @test */
    public function it_does_not_fire_normally()
    {
        $this->assertFalse($this->hook->fires());
    }

    /** @test */
    public function it_fires_for_tenant_events()
    {
        $this->hook->event = new Created($this->mockTenant());
        $this->assertTrue($this->hook->fires());
    }

    /** @test */
    public function it_is_queued_by_default()
    {
        $this->assertTrue(
            $this->hook->queued()
        );
    }

    /** @test */
    public function it_returns_null_if_no_queue_is_set()
    {
        $this->assertNull($this->hook->queue());
    }
}
