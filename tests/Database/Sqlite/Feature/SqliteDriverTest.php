<?php

namespace Tenancy\Tests\Database\Sqlite\Feature;

use Tenancy\Database\Drivers\Sqlite\Provider;
use Tenancy\Tests\Database\DatabaseFeatureTestCase;
use Tenancy\Tests\UsesConnections;

class SqliteDriverTest extends DatabaseFeatureTestCase
{
    use UsesConnections;

    protected $additionalProviders = [Provider::class];

    protected function registerDatabaseListener()
    {
        $this->configureBoth(function ($event) {
            $event->useConfig($this->getSqliteConfigurationPath(), [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        });
    }
}
