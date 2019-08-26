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

use Illuminate\Support\Facades\DB;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migrations\Provider;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Hooks\Database\Support\InteractsWithDatabases;
use Tenancy\Hooks\Migrations\Provider as MigrationsProvider;
use Tenancy\Hooks\Migrations\Support\InteractsWithMigrations;
use Tenancy\Tests\Affects\Connection\Mocks\ConnectionListener;
use Tenancy\Affects\Connection\Provider as ConnectionProvider;
use Tenancy\Database\Drivers\Sqlite\Provider as SQLiteProvider;
use Tenancy\Affects\Connection\Support\InteractsWithConnections;

class SeedsHookTest extends TestCase
{
    protected $additionalProviders = [SQLiteProvider::class, MigrationsProvider::class, ConnectionProvider::class, DatabaseProvider::class];

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
        $this->registerSeederFile(__DIR__.'/seeds/MockSeeder.php');

        $this->defaultConnection = DB::getDefaultConnection();

        $callback = function ($event) {
            $event->useConfig(__DIR__.DIRECTORY_SEPARATOR.'database.php', [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        };


        $this->resolveConnection(function(){
            return new ConnectionListener();
        });

        $this->configureConnection($callback);
        $this->configureDatabase($callback);

        $this->events->dispatch(new Created($this->tenant));
    }

    /**
     * @test
     */
    public function seeds()
    {
        Tenancy::getTenant();

        $this->assertNotNull(
            DB::connection(Tenancy::getTenantConnectionName())
                ->table('mocks')
                ->find(5)
        );

        DB::disconnect(Tenancy::getTenantConnectionName());
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
