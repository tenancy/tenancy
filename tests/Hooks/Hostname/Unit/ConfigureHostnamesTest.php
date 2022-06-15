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

namespace Tenancy\Tests\Hooks\Hostname\Unit;

use Illuminate\Support\Facades\Event;
use Tenancy\Hooks\Hostname\Events\ConfigureHostnames;
use Tenancy\Hooks\Hostname\Hooks\HostnamesHook;
use Tenancy\Hooks\Hostname\Provider;
use Tenancy\Tests\Hooks\ConfigureHookTestCase;
use Tenancy\Tests\Mocks\Hostname\SimpleHandler;
use Tenancy\Tests\Mocks\Tenants\HostnameTenant;

class ConfigureHostnamesTest extends ConfigureHookTestCase
{
    protected array $additionalProviders = [Provider::class];

    protected $hookClass = HostnamesHook::class;

    protected $eventClass = ConfigureHostnames::class;

    public function getMockedTenant()
    {
        $this->createMockTenant();

        return HostnameTenant::first();
    }

    /**
     * @dataProvider tenantEventsProvider
     *
     * @test */
    public function it_can_forwards_call_to_the_hook($tenantEvent)
    {
        Event::listen(ConfigureHostnames::class, function (ConfigureHostnames $event) {
            $this->assertIsArray($event->getHandlers());
        });

        $this->hook->for(new $tenantEvent($this->getMockedTenant()));
    }

    /**
     * @dataProvider tenantEventsProvider
     *
     * @test */
    public function it_can_register_handlers($tenantEvent)
    {
        Event::listen(ConfigureHostnames::class, function (ConfigureHostnames $event) {
            $this->assertEmpty($event->getHandlers());

            $event->registerHandler(new SimpleHandler());

            $this->assertNotEmpty($event->getHandlers());
        });

        $this->hook->for(new $tenantEvent($this->getMockedTenant()));
    }
}
