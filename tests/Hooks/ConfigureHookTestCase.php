<?php

namespace Tenancy\Tests\Hooks;

use Tenancy\Testing\TestCase;
use Tenancy\Tenant\Events as Tenant;

abstract class ConfigureHookTestCase extends TestCase
{
    /** @var string */
    protected $hookClass;

    /** @var string */
    protected $eventClass;

    protected $hook;

    protected function afterSetUp()
    {
        $this->hook = $this->app->make($this->hookClass);
    }

    /**
     * @dataProvider tenantEventsProvider
     *
     * @test */
    public function it_can_disable_the_hook($tenantEvent)
    {
        $this->events->listen($this->eventClass, function ($event){
            $event->disable();
        });

        $this->hook->for(new $tenantEvent($this->mockTenant()));

        $this->assertFalse($this->hook->fires);
    }

    /**
     * @dataProvider tenantEventsProvider
     *
     * @test */
    public function it_can_prioritize_the_hook($tenantEvent)
    {
        $original = $this->hook->priority();

        $this->events->listen($this->eventClass, function ($event){
            $event->priority(-10000);
        });

        $this->hook->for(new $tenantEvent($this->mockTenant()));

        $this->assertNotEquals(
            $original,
            $this->hook->priority()
        );
    }

    public function tenantEventsProvider()
    {
        return [
            [Tenant\Created::class],
            [Tenant\Updated::class],
            [Tenant\Deleted::class],
        ];
    }
}
