<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database\Sqlite;

use Tenancy\Database\Drivers\Sqlite\Provider;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;

class ConfiguresSqliteTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

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

        $config = config('database.connections.sqlite');
        $config['database'] = $tenant->getTenantKey().'.sqlite';

        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConnection('sqlite', [
                'database' => $event->tenant->getTenantKey().'.sqlite',
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
