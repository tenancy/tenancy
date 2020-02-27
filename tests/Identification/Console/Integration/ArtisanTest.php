<?php

namespace Tenancy\Tests\Identification\Console\Integration;

use Illuminate\Foundation\Console\Kernel;
use Tenancy\Environment;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Console\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;

class ArtisanTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    protected function afterSetUp()
    {
        $this->app->make(Kernel::class)->command(
            'identifies',
            function () {
            }
        );
    }

    /** @test */
    public function it_checks_if_a_tenant_is_identified()
    {
        $this->mock(Environment::class, function ($mock){
            $mock
                ->shouldReceive('isIdentified')
                ->once();
            $mock->makePartial();
        });

        $this->artisan('identifies');
    }

    /** @test */
    public function it_does_not_trigger_identification_when_a_tenant_is_already_identified()
    {
        $this->mock(Environment::class, function ($mock){
            $mock
                ->shouldReceive('isIdentified')
                ->andReturn(true);
            $mock
                ->shouldNotReceive('identifyTenant');
        });
    }

    /** @test */
    public function it_triggers_console_identification()
    {
        $this->mock(Environment::class, function ($mock){
            $mock
                ->shouldReceive('identifyTenant')
                ->withArgs([false, IdentifiesByConsole::class])
                ->once();
            $mock->makePartial();
        });

        $this->artisan('identifies');
    }

    /** @test */
    public function it_can_identify_null()
    {
        $this->artisan('identifies');

        $this->assertNull($this->environment->getTenant());
    }
}