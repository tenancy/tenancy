<?php

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
        factory(Tenant::class)->create();
        return HostnameTenant::first();
    }
}
