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

namespace Tenancy\Tests\Hooks\Hostname\Feature;

use Mockery;
use Tenancy\Hooks\Hostname\Events\ConfigureHostnames;
use Tenancy\Hooks\Hostname\Provider;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Hostname\SimpleHandler;
use Tenancy\Tests\Mocks\Tenants\HostnameTenant;

class HandlesTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /** @test */
    public function it_fires_the_handle_function_on_all_handlers()
    {
        $handler = Mockery::mock(new SimpleHandler());
        $otherHandler = Mockery::mock(new SimpleHandler());

        $this->events->listen(ConfigureHostnames::class, function (ConfigureHostnames $event) use ($handler, $otherHandler) {
            $event->registerHandler($handler);
            $event->registerHandler($otherHandler);
        });

        $this->events->dispatch(new Created($this->getHostnameTenant()));

        $handler->shouldHaveReceived('handle');
        $otherHandler->shouldHaveReceived('handle');
    }

    private function getHostnameTenant()
    {
        Tenant::factory()->create();

        return HostnameTenant::first();
    }
}
