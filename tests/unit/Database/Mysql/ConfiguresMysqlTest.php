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

namespace Tenancy\Tests\Database\Mysql;

use Tenancy\Database\Drivers\Mysql\Provider;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Testing\TestCase;

class ConfiguresMysqlTest extends TestCase
{
    protected $additionalProviders = [Provider::class, DatabaseProvider::class];

    /**
     * @test
     */
    public function reads_config_file()
    {
        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConfig(__DIR__.'/database.php');
        });

        $this->resolveTenant($tenant = $this->mockTenant());
        Tenancy::getTenant();

        $config = include __DIR__.'/database.php';

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
                'database' => $event->tenant->getTenantKey(),
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
