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

namespace Tenancy\Tests\Hooks\Migrations;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tenancy\Affects\Connection\Provider as ConnectionProvider;
use Tenancy\Affects\Connection\Support\InteractsWithConnections;
use Tenancy\Database\Drivers\Sqlite\Provider as SQLiteProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Hooks\Database\Support\InteractsWithDatabases;
use Tenancy\Hooks\Migrations\Events\ConfigureMigrations;
use Tenancy\Hooks\Migrations\Provider as MigrationsProvider;
use Tenancy\Hooks\Migrations\Support\InteractsWithMigrations;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Affects\Connection\Mocks\ConnectionListener;

class ConfiguresMigrationsTest extends TestCase
{
    protected $additionalProviders = [SQLiteProvider::class, MigrationsProvider::class, ConnectionProvider::class, DatabaseProvider::class];

    use InteractsWithDatabases,
        InteractsWithConnections,
        InteractsWithMigrations;

    /**
     * @var \Tenancy\Testing\Mocks\Tenant
     */
    protected $tenant;

    public function afterSetUp()
    {
        $this->resolveTenant($this->tenant = $this->mockTenant([
            'id' => 3607,
        ]));

        $this->registerMigrationsPath(__DIR__.'/database/');

        $this->resolveConnection(function () {
            return new ConnectionListener();
        });

        $callback = function ($event) {
            $event->useConfig(__DIR__.DIRECTORY_SEPARATOR.'database.php', [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        };

        $this->configureDatabase($callback);
        $this->configureConnection($callback);

        config(['tenancy.database.auto-create' => true]);
        config(['tenancy.database.auto-update' => true]);
        config(['tenancy.database.auto-delete' => true]);
    }

    protected function cleanDatabase()
    {
        DB::purge(Tenancy::getTenantConnectionName());
        $this->events->dispatch(new Deleted($this->tenant));
    }

    /**
     * @test
     */
    public function can_disable()
    {
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->disable();
        });

        $this->events->dispatch(new Created($this->tenant));

        Tenancy::setTenant($this->tenant);

        $this->assertFalse(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        $this->cleanDatabase();
    }

    /**
     * @test
     */
    public function can_set_priority()
    {
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->priority(-1000);
        });

        $this->expectException(QueryException::class);

        $this->events->dispatch(new Created($this->tenant));

        $this->cleanDatabase();
    }
}
