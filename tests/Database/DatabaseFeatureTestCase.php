<?php

namespace Tenancy\Tests\Database;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\ConnectionListener;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Affects\Connections\Provider as ConnectionProvider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\Concerns\InteractsWithConnections;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Tenant\Events;

abstract class DatabaseFeatureTestCase extends TestCase
{
    /** @var DatabaseManager */
    protected $db;

    /** @var Tenant */
    protected $tenant;

    use InteractsWithDatabases;
    use InteractsWithConnections;

    protected function afterSetUp()
    {
        $this->db = $this->app->make(DatabaseManager::class);
        $this->tenant = $this->createMockTenant();
        $this->tenant->unguard();

        $this->resolveTenant($this->tenant);

        $this->app->register(ConnectionProvider::class);
        $this->app->register(DatabaseProvider::class);

        $this->resolveConnection(function () {
            return new ConnectionListener();
        });
        $this->registerDatabaseListener();
    }

    abstract protected function registerDatabaseListener();


    /** @test */
    public function it_creates_the_database()
    {
        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertInstanceOf(
            \PDO::class,
            $this->getTenantConnection()->getPdo()
        );
    }

    /** @test */
    public function it_updates_the_database()
    {
        $this->events->dispatch(new Events\Created($this->tenant));

        $this->tenant->id = 1997;
        $this->events->dispatch(new Events\Updated($this->tenant));

        $this->assertInstanceOf(
            \PDO::class,
            $this->getTenantConnection()->getPdo()
        );
    }

    /** @test */
    public function it_deletes_the_database()
    {
        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertInstanceOf(
            \PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->db->purge(Tenancy::getTenantConnectionName());

        $this->events->dispatch(new Events\Deleted($this->tenant));

        $this->expectException(QueryException::class);
        $this->getTenantConnection()->getPdo();
    }

    protected function configureBoth(\Closure $callback)
    {
        $this->configureDatabase($callback);
        $this->configureConnection($callback);
    }

    protected function getTenantConnection()
    {
        Tenancy::identifyTenant();

        return $this->db->connection(Tenancy::getTenantConnectionName());
    }
}
