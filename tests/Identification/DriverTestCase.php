<?php

namespace Tenancy\Tests\Identification;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\TenantResolver;

abstract class DriverTestCase extends TestCase
{
    /** @var array */
    protected $drivers = [];

    /** @var string */
    protected $provider;

    protected function afterSetUp()
    {
        $this->app->bind(ResolvesTenants::class, TenantResolver::class);
    }

    /** @test */
    public function all_drivers_are_registered()
    {
        $this->app->register($this->provider);

        foreach ($this->drivers as $driver) {
            $this->assertContains($driver, $this->getResolver()->drivers);
        }
    }

    /** @test */
    public function only_these_drivers_are_registered()
    {
        $this->app->register($this->provider);

        $this->assertCount(
            count($this->drivers),
            $this->getResolver()->drivers
        );
    }

    /**
     * Gets the resolver from the application
     *
     * @return ResolvesTenants
     */
    protected function getResolver()
    {
        return $this->app->make(ResolvesTenants::class);
    }
}
