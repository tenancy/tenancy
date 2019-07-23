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
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class SeedsHookTest extends TestCase
{
    protected $additionalProviders = [DatabaseProvider::class, Provider::class];
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

        $this->migrateTenant(__DIR__.'/database/');
        $this->seedTenant(__DIR__.'/seeds/MockSeeder.php');

        $this->defaultConnection = DB::getDefaultConnection();

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
