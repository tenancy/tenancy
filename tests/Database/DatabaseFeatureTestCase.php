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

namespace Tenancy\Tests\Database;

use Illuminate\Database\DatabaseManager;
use Tenancy\Affects\Connections\Provider as ConnectionProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Hooks\Migration\Events\ConfigureMigrations;
use Tenancy\Hooks\Migration\Provider as MigrationProvider;
use Tenancy\Hooks\Migration\Support\InteractsWithMigrations;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Tenant\Events;
use Tenancy\Testing\Concerns\InteractsWithConnections;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\ConnectionListener;
use Tenancy\Tests\Mocks\Models\TenantModel;
use Tenancy\Tests\UsesMigrations;
use Tenancy\Tests\UsesModels;

abstract class DatabaseFeatureTestCase extends TestCase
{
    /** @var DatabaseManager */
    protected $db;

    /** @var TenantContract */
    protected $tenant;

    /** @var string */
    protected $tenantModel = Tenant::class;

    /** @var string */
    protected $exception = \PDOException::class;

    use InteractsWithDatabases;
    use InteractsWithConnections;
    use InteractsWithMigrations;
    use UsesMigrations;
    use UsesModels;

    protected function afterSetUp()
    {
        $this->db = $this->app->make(DatabaseManager::class);
        $this->tenant = factory($this->tenantModel)->create();
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

        $this->cleanDatabase($this->tenant);
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

        $this->cleanDatabase($this->tenant);
    }

    /** @test */
    public function updating_the_same_tenant_does_not_change_the_connection()
    {
        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertInstanceOf(
            \PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->events->dispatch(new Events\Updated($this->tenant));

        $this->assertInstanceOf(
            \PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->cleanDatabase($this->tenant);
    }

    /** @test */
    public function updating_keeps_the_data()
    {
        $this->app->register(MigrationProvider::class);
        $this->registerModelFactories();
        $this->registerMigrationsPath($this->getMigrationsPath());
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            if ($event->event instanceof Events\Deleted) {
                $event->disable();
            }
        });

        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertTrue(
            $this->getTenantConnection()
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        factory(TenantModel::class, 10)->create();
        $mocks = TenantModel::all();

        $this->db->purge(Tenancy::getTenantConnectionName());

        $this->tenant->id = 1997;
        $this->events->dispatch(new Events\Updated($this->tenant));

        Tenancy::setTenant($this->tenant);

        $this->assertTrue(
            $this->getTenantConnection()
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        $this->assertEquals(
            $mocks,
            TenantModel::all()
        );
        $this->cleanDatabase($this->tenant);
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

        $this->expectException($this->exception);
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

    protected function cleanDatabase(Tenant $tenant = null)
    {
        $this->db->purge(Tenancy::getTenantConnectionName());

        if ($tenant == null) {
            $tenant = $this->tenant;
        }

        $this->events->dispatch(new Events\Deleted($tenant));
    }
}
