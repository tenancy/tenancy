<?php

namespace Tenancy\Tests\Framework\Unit;

use Tenancy\Environment;
use Tenancy\Testing\TestCase;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function it_is_registered_as_singleton()
    {
        $tenant = $this->mockTenant();

        $environment = $this->app->make(Environment::class);
        $environment->setTenant($tenant);

        $newEnvironment = resolve(Environment::class);

        $this->assertEquals(
            $tenant,
            $newEnvironment->getTenant()
        );

        $this->assertEquals(
            $environment,
            $newEnvironment
        );
    }
}
