<?php

namespace Tenancy\Tests\Hooks\Migration\Unit;

use Tenancy\Affects\Connections\Provider as ConnectionsProvider;
use Tenancy\Hooks\Migration\Events\ConfigureMigrations;
use Tenancy\Hooks\Migration\Hooks\MigratesHook;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tests\Hooks\ConfigureHookTestCase;

class ConfiguresMigrationsTest extends ConfigureHookTestCase
{
    protected $additionalProviders = [ConnectionsProvider::class];

    protected $hookClass = MigratesHook::class;

    protected $eventClass = ConfigureMigrations::class;

    /**
     * @dataProvider tenantEventsProvider
     *
     * @test */
    public function it_can_add_paths_to_the_migrator($tenantEvent)
    {
        $this->events->listen($this->eventClass, function ($event){
            $event->path(__DIR__);
        });

        $this->hook->for(new $tenantEvent($this->mockTenant()));

        $this->assertContains(
            __DIR__,
            $this->hook->migrator->paths()
        );
    }
}
