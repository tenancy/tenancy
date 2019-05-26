<?php

namespace Tenancy\Tests\Database;

use Tenancy\Database\Drivers\Mysql\Providers\DatabaseProvider;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;

class ConfiguresMysqlTest extends TestCase
{
    protected $additionalProviders = [DatabaseProvider::class];

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
}