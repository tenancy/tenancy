<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\Migrations;

use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Affects\Migrations\Providers\ServiceProvider;
use Tenancy\Affects\Migrations\Events\ConfigureMigrations;
use Tenancy\Database\Drivers\Sqlite\Providers\DatabaseProvider;

class MigratesHookTest extends TestCase
{
    protected $additionalProviders = [ServiceProvider::class, DatabaseProvider::class];

    public function afterSetUp()
    {
        $this->tenant = $this->mockTenant([
            'id' => 3607
        ]);

        $this->resolveTenant($this->tenant);
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) {
            $event->path(__DIR__ . '/database/');
        });

        $this->events->dispatch(new Created($this->tenant));
    }

    /**
     * @test
     */
    public function migrates()
    {
        Tenancy::getTenant();

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
        $this->assertTrue(
            DB::connection(Tenancy::getTenantConnectionName())
            ->getSchemaBuilder()
            ->hasTable('mocks')
        );

        // Disable auto delete as it would delete the DB before we can rollback
        config(['tenancy.database.auto-delete' => false]);
        $this->events->dispatch(new Deleted($this->tenant));

        Tenancy::getTenant();

        $this->assertFalse(
            DB::connection(Tenancy::getTenantConnectionName())
            ->getSchemaBuilder()
            ->hasTable('mocks')
        );
    }
}
