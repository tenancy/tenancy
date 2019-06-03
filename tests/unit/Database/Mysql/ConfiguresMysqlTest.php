<?php

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

namespace Tenancy\Tests\Database;

use Tenancy\Database\Drivers\Mysql\Providers\ServiceProvider;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;

class ConfiguresMysqlTest extends TestCase
{
    protected $additionalProviders = [ServiceProvider::class];

    /**
     * @test
     */
    public function reads_config_file()
    {
        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConfig(__DIR__ . '/database.php');
        });

        $this->resolveTenant($tenant = $this->mockTenant());
        Tenancy::getTenant();

        $config = include __DIR__ . '/database.php';

        $config['tenant-key'] = $tenant->getTenantKey();
        $config['tenant-identifier'] = $tenant->getTenantIdentifier();

        $this->assertEquals(
            $config,
            config('database.connections.tenant')
        );
    }

    /**
     * @test
     */
    public function mimicks_connection()
    {
        $this->resolveTenant($tenant = $this->mockTenant());

        $config = config('database.connections.mysql');
        $config['database'] = $tenant->getTenantKey();

        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConnection('mysql', [
                'database' => $event->tenant->getTenantKey()
            ]);
        });

        Tenancy::getTenant();

        $config['tenant-key'] = $tenant->getTenantKey();
        $config['tenant-identifier'] = $tenant->getTenantIdentifier();

        $this->assertEquals(
            $config,
            config('database.connections.tenant')
        );
    }
}
