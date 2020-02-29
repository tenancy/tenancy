<?php

namespace Tenancy\Tests\Hooks\Migration\Unit;

use Illuminate\Support\Facades\Event;
use Tenancy\Affects\Connections\Provider as ConnectionsProvider;
use Tenancy\Hooks\Migration\Events\ConfigureMigrations;
use Tenancy\Hooks\Migration\Hooks\MigratesHook;
use Tenancy\Hooks\Migration\Provider;
use Tenancy\Tenant\Events as Tenant;
use Tenancy\Testing\TestCase;

class MigratesHookTest extends TestCase
{
    protected $additionalProviders = [ConnectionsProvider::class];

    protected function afterSetUp()
    {
        $this->hook = $this->app->make(MigratesHook::class);
    }

    /** @test */
    public function it_is_enabled_by_default()
    {
        $this->assertTrue(
            $this->hook->fires
        );
    }

    /** @test */
    public function it_fires_the_configure_migrations_event_when_running_for()
    {
        Event::fake(ConfigureMigrations::class);

        $this->hook->for(new Tenant\Created($this->mockTenant()));

        Event::assertDispatched(ConfigureMigrations::class);
    }

    /** @test */
    public function on_created_the_action_is_run()
    {
        $this->hook->for(new Tenant\Created($this->mockTenant()));

        $this->assertEquals(
            "run",
            $this->hook->action
        );
    }

    /** @test */
    public function on_updated_the_action_is_run()
    {
        $this->hook->for(new Tenant\Updated($this->mockTenant()));

        $this->assertEquals(
            "run",
            $this->hook->action
        );
    }

    /** @test */
    public function on_deleted_the_action_is_reset()
    {
        $this->hook->for(new Tenant\Deleted($this->mockTenant()));

        $this->assertEquals(
            "reset",
            $this->hook->action
        );
    }
}
