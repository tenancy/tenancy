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

namespace Tenancy\Tests\Hooks\Database;

use Closure;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use PDO;
use Tenancy\Affects\Connections\Provider as ConnectionProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Hooks\Migration\Provider as MigrationProvider;
use Tenancy\Hooks\Migration\Support\InteractsWithMigrations;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Tenant\Events;
use Tenancy\Testing\Concerns\InteractsWithConnections;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Affects\Connections\Mocks\ConnectionListener;
use Tenancy\Tests\Hooks\Database\Mocks\Mock;

abstract class DatabaseDriverTestCase extends TestCase
{
    use InteractsWithMigrations,
        InteractsWithDatabases,
        InteractsWithConnections;

    protected $db;

    protected $tenant;

    protected $tenantModel = Tenant::class;

    protected $exception = QueryException::class;

    protected $additionalMocks = [__DIR__.'/Mocks/factories'];

    protected function afterSetUp()
    {
        $this->registerModel();

        $this->db = resolve(DatabaseManager::class);

        $this->tenant = factory($this->tenantModel)->create([
            'id' => 1803,
        ]);
        $this->tenant->unguard();

        $this->resolveTenant($this->tenant);

        $this->app->register(ConnectionProvider::class);
        $this->app->register(DatabaseProvider::class);

        $this->registerDatabaseListener();

        $this->resolveConnection(function () {
            return new ConnectionListener();
        });
    }

    abstract protected function registerDatabaseListener();

    protected function getTenantConnection()
    {
        Tenancy::getTenant();

        return $this->db->connection(Tenancy::getTenantConnectionName());
    }

    protected function cleanDatabases(TenantContract $tenant = null)
    {
        $this->db->purge(Tenancy::getTenantConnectionName());

        if ($tenant == null) {
            $tenant = $this->tenant;
        }

        $this->events->dispatch(new Events\Deleted($tenant));
    }

    protected function registerModel()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel($this->tenantModel);
    }

    protected function configureBoth(Closure $callback)
    {
        $this->configureDatabase($callback);
        $this->configureConnection($callback);
    }

    /**
     * @test
     */
    public function runs_create()
    {
        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertInstanceOf(
            PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->cleanDatabases();
    }

    /**
     * @test
     */
    public function prevent_same_update()
    {
        $this->events->dispatch(new Events\Created($this->tenant));
        $this->events->dispatch(new Events\Updated($this->tenant));

        $this->assertInstanceOf(
            PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->cleanDatabases();
    }

    /**
     * @test
     */
    public function runs_update()
    {
        $this->events->dispatch(new Events\Created($this->tenant));

        $this->tenant->id = 1997;
        $this->events->dispatch(new Events\Updated($this->tenant));

        $this->assertInstanceOf(
            PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->cleanDatabases();
    }

    /**
     * @test
     */
    public function runs_update_moves_tables()
    {
        $this->app->register(MigrationProvider::class);
        $this->registerMigrationsPath(__DIR__.DIRECTORY_SEPARATOR.'migrations');

        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertTrue(
            $this->getTenantConnection()
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        factory(Mock::class, 10)->create();
        $mocks = Mock::all();

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
            Mock::all()
        );
    }

    /**
     * @test
     */
    public function runs_delete()
    {
        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertInstanceOf(
            PDO::class,
            $this->getTenantConnection()->getPdo()
        );

        $this->db->purge(Tenancy::getTenantConnectionName());

        $this->events->dispatch(new Events\Deleted($this->tenant));

        $this->expectException($this->exception);
        $this->getTenantConnection()->getPdo();
    }
}
