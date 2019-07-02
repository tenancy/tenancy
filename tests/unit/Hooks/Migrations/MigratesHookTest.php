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
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\Migrations;

use Illuminate\Support\Facades\DB;
use Tenancy\Database\Drivers\Sqlite\Provider as DatabaseProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migrations\Provider;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class MigratesHookTest extends TestCase
{
    protected $additionalProviders = [DatabaseProvider::class, Provider::class];
    /**
     * @var Tenant
     */
    protected $tenant;

    public function afterSetUp()
    {
        $this->resolveTenant($this->tenant = $this->mockTenant());

        $this->migrateTenant(__DIR__.'/database/');

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

//        Tenancy::getTenant();

        $this->assertFalse(
            DB::connection(Tenancy::getTenantConnectionName())
                ->getSchemaBuilder()
                ->hasTable('mocks')
        );
    }
}
