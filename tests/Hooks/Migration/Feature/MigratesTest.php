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

namespace Tenancy\Tests\Hooks\Migration\Feature;

use Illuminate\Support\Facades\DB;
use Tenancy\Affects\Connections\Events\Drivers\Configuring;
use Tenancy\Affects\Connections\Events\Resolving;
use Tenancy\Affects\Connections\Provider as ConnectionsProvider;
use Tenancy\Database\Drivers\Sqlite\Provider as SqliteProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Hooks\Migration\Provider;
use Tenancy\Hooks\Migration\Support\InteractsWithMigrations;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\Concerns\InteractsWithConnections;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Connections\ConnectionResolvingListener;
use Tenancy\Tests\UsesConnections;
use Tenancy\Tests\UsesMigrations;

class MigratesTest extends TestCase
{
    use InteractsWithMigrations;
    use InteractsWithConnections;
    use InteractsWithDatabases;
    use UsesConnections;
    use UsesMigrations;

    protected $additionalProviders = [Provider::class, DatabaseProvider::class, ConnectionsProvider::class, SqliteProvider::class];

    /** @test */
    public function it_can_migrate_a_database()
    {
        $this->registerTenantConnection();

        $tenant = $this->createMockTenant();

        $this->events->dispatch(new Created($tenant));

        Tenancy::setTenant($tenant);

        $this->assertFalse(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        $this->registerMigrationsPath($this->getMigrationsPath());

        $this->events->dispatch(new Created($tenant));

        Tenancy::setTenant($tenant);

        $this->assertTrue(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );
    }

    /**
     * Registers the tenant related logic in the application.
     *
     * @return void
     */
    private function registerTenantConnection()
    {
        $this->resolveConnection(function (Resolving $event) {
            return new ConnectionResolvingListener();
        });
        $this->configureConnection(function (Configuring $event) {
            $event->useConfig($this->getSqliteConfigurationPath(), [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        });
        $this->configureDatabase(function ($event) {
            $event->useConfig($this->getSqliteConfigurationPath(), [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        });
    }
}
