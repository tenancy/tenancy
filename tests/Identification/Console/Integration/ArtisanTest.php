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

namespace Tenancy\Tests\Identification\Console\Integration;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Console\Kernel;
use Tenancy\Environment;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Console\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;

class ArtisanTest extends TestCase
{
    protected array $additionalProviders = [IdentificationProvider::class];

    protected function afterSetUp()
    {
        $kernel = $this->app[ConsoleKernel::class];

        if (method_exists($kernel, 'rerouteSymfonyCommandEvents')) {
            $kernel->rerouteSymfonyCommandEvents();
        }

        $this->app->make(Kernel::class)->command(
            'identifies',
            function () {
            }
        );
    }

    /** @test */
    public function it_checks_if_a_tenant_is_identified()
    {
        $this->mock(Environment::class, function ($mock) {
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
        $this->mock(Environment::class, function ($mock) {
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
        $this->mock(Environment::class, function ($mock) {
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
