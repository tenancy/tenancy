<?php

namespace Tenancy\Tests\Database;

use Illuminate\Database\ConnectionInterface;
use Tenancy\Database\Contracts\ResolvesConnections;
use Tenancy\Database\Events\Resolving;
use Tenancy\Facades\Tenancy;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class DatabaseResolverTest extends TestCase
{
    /**
     * @var ResolvesConnections
     */
    protected $resolver;
    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->events->forget(Resolving::class);

        $this->events->listen(Resolving::class, function (Resolving $event) {
            return new Mocks\DatabaseDriver;
        });
        $this->resolveTenant($this->tenant = $this->mockTenant());
    }

    /**
     * @test
     */
    public function resolves_driver()
    {
        $this->events->dispatch(new Created($this->tenant));

        /** @var ConnectionInterface $connection */
        $connection = Tenancy::getTenantConnection();

        $this->assertNotNull($connection);

        $this->assertEquals(Tenancy::getTenantConnectionName(), $connection->getConfig('name'));
        $this->assertEquals($this->tenant->getTenantKey(), $connection->getConfig('tenant-key'));
    }
}