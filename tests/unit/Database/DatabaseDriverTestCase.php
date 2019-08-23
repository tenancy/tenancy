<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use PDO;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migrations\Provider;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Tenant\Events;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Database\Mocks\Mock;

abstract class DatabaseDriverTestCase extends TestCase
{
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

        $this->registerDatabaseListener();
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

        if ($tenant === null) {
            $tenant = $this->tenant;
        }

        // $this->events->dispatch(new Events\Deleted($tenant));
    }

    protected function registerModel()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel($this->tenantModel);
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
        $this->app->register(Provider::class);
        $this->migrateTenant(__DIR__.DIRECTORY_SEPARATOR.'migrations');

        $this->events->dispatch(new Events\Created($this->tenant));

        $this->assertTrue(
            $this->getTenantConnection()
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        factory(Mock::class, 10)->create();
        $mocks = Mock::all();

        $this->db->disconnect(Tenancy::getTenantConnectionName());

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
