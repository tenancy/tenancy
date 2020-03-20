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

namespace Tenancy\Tests\Hooks\Migration\Unit;

use Tenancy\Affects\Connections\Provider as ConnectionsProvider;
use Tenancy\Hooks\Migration\Events\ConfigureSeeds;
use Tenancy\Hooks\Migration\Hooks\SeedsHook;
use Tenancy\Tests\Hooks\ConfigureHookTestCase;

class ConfiguresSeedsTest extends ConfigureHookTestCase
{
    protected $additionalProviders = [ConnectionsProvider::class];

    protected $hookClass = SeedsHook::class;

    protected $eventClass = ConfigureSeeds::class;

    /**
     * @dataProvider tenantEventsProvider
     *
     * @test */
    public function it_can_add_seeds($tenantEvent)
    {
        $this->events->listen($this->eventClass, function ($event) {
            $event->seed(__DIR__);
        });

        $this->hook->for(new $tenantEvent($this->mockTenant()));

        $this->assertContains(
            __DIR__,
            $this->hook->seeds
        );
    }
}
