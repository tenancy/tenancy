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

namespace Tenancy\Tests\Hooks\Migration;

use Illuminate\Support\Facades\DB;
use Tenancy\Affects\Connections\Provider as ConnectionProvider;
use Tenancy\Database\Drivers\Sqlite\Provider as SQLiteProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Hooks\Migration\Provider as MigrationProvider;
use Tenancy\Hooks\Migration\Support\InteractsWithMigrations;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Testing\Concerns\InteractsWithConnections;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Affects\Connections\Mocks\ConnectionListener;

class MigratesHookTest extends TestCase
{
    protected $additionalProviders = [SQLiteProvider::class, MigrationProvider::class, ConnectionProvider::class, DatabaseProvider::class];

    use InteractsWithDatabases,
        InteractsWithConnections,
        InteractsWithMigrations;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var string
     */
    protected $defaultConnection;

    public function afterSetUp()
    {
        $this->resolveTenant($this->tenant = $this->mockTenant());

        $this->registerMigrationsPath(__DIR__.'/database/');

        $this->defaultConnection = DB::getDefaultConnection();

        $callback = function ($event) {
            $event->useConfig(__DIR__.DIRECTORY_SEPARATOR.'database.php', [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        };

        $this->resolveConnection(function () {
            return new ConnectionListener();
        });

        $this->configureConnection($callback);
        $this->configureDatabase($callback);
        config(['tenancy.database.auto-create' => true]);
        config(['tenancy.database.auto-update' => true]);
        config(['tenancy.database.auto-delete' => true]);

        $this->events->dispatch(new Created($this->tenant));
    }

    /**
     * @test
     */
    public function migrates()
    {
        Tenancy::identifyTenant();

        $this->assertTrue(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        DB::disconnect(Tenancy::getTenantConnectionName());
    }

    /**
     * @test
     */
    public function resets()
    {
        Tenancy::identifyTenant();

        $this->assertTrue(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        Tenancy::setTenant(null);
        // Disable auto delete as it would delete the DB before we can rollback
        config(['tenancy.database.auto-delete' => false]);
        $this->events->dispatch(new Deleted($this->tenant));

        Tenancy::identifyTenant(true);

        $this->assertFalse(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );
    }

    /**
     * @test
     */
    public function restores_default_connection()
    {
        $this->assertEquals(
            $this->defaultConnection,
            DB::getDefaultConnection()
        );
    }
}
