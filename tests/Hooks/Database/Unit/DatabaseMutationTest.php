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

namespace Tenancy\Tests\Hooks\Database\Unit;

use Illuminate\Support\Facades\Event;
use Tenancy\Hooks\Database\Events\ConfigureDatabaseMutation;
use Tenancy\Hooks\Database\Hooks\DatabaseMutation;
use Tenancy\Hooks\Database\Provider;
use Tenancy\Identification\Events\Switched;
use Tenancy\Tenant\Events as Tenant;
use Tenancy\Testing\TestCase;

class DatabaseMutationTest extends TestCase
{
    protected array $additionalProviders = [Provider::class];

    protected function afterSetUp()
    {
        $this->hook = $this->app->make(DatabaseMutation::class);
    }

    /** @test */
    public function it_is_not_fired_for_switched()
    {
        $this->hook->for(new Switched($this->mockTenant()));

        $this->assertFalse(
            $this->hook->fires()
        );
    }

    /** @test */
    public function it_is_fired_for_created()
    {
        $this->hook->for(new Tenant\Created($this->mockTenant()));

        $this->assertTrue(
            $this->hook->fires()
        );
    }

    /** @test */
    public function it_is_fired_for_updated()
    {
        $this->hook->for(new Tenant\Updated($this->mockTenant()));

        $this->assertTrue(
            $this->hook->fires()
        );
    }

    /** @test */
    public function it_is_fired_for_deleted()
    {
        $this->hook->for(new Tenant\Deleted($this->mockTenant()));

        $this->assertTrue(
            $this->hook->fires()
        );
    }

    /** @test */
    public function it_fires_configure_database_mutation_for_tenant_events()
    {
        Event::fake([ConfigureDatabaseMutation::class]);

        $this->hook->for(new Tenant\Created($this->mockTenant()));
        $this->hook->for(new Tenant\Updated($this->mockTenant()));
        $this->hook->for(new Tenant\Deleted($this->mockTenant()));

        Event::assertDispatchedTimes(ConfigureDatabaseMutation::class, 3);
    }

    /** @test */
    public function it_does_not_fire_configure_database_mutation_for_other_events()
    {
        Event::fake([ConfigureDatabaseMutation::class]);

        $this->hook->for(new Switched($this->mockTenant()));

        Event::assertNotDispatched(ConfigureDatabaseMutation::class);
    }
}
