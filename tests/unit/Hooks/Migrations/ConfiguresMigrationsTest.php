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
use PDOException;
use Tenancy\Database\Drivers\Sqlite\Provider as DatabaseProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migrations\Events\ConfigureMigrations;
use Tenancy\Hooks\Migrations\Provider;
use Tenancy\Tenant\Events;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\TestCase;

class ConfiguresMigrationsTest extends TestCase
{
    protected $additionalProviders = [DatabaseProvider::class, Provider::class];
    /**
     * @var \Tenancy\Testing\Mocks\Tenant
     */
    protected $tenant;

    public function afterSetUp()
    {
        $this->resolveTenant($this->tenant = $this->mockTenant([
            'id' => 3607,
        ]));

        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->path(__DIR__.'/database/');
        });

        $callback = function ($event) {
            $event->useConfig(__DIR__.DIRECTORY_SEPARATOR.'database.php', [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        };
        $this->configureDatabase($callback);
        $this->configureConnection($callback);
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

        Tenancy::getTenant();
        $this->assertFalse(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );

        // Clean DB After
        DB::purge(Tenancy::getTenantConnectionName());
        $this->events->dispatch(new Events\Deleted($this->tenant));
    }

    /**
     * @test
     */
    public function can_set_priority()
    {
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->priority(-1000);
        });

        $this->expectException(PDOException::class);

        $this->events->dispatch(new Created($this->tenant));
    }
}
