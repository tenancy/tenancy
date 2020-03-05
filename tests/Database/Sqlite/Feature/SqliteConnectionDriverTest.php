<?php

namespace Tenancy\Tests\Database\Sqlite\Feature;

use Illuminate\Database\QueryException;
use Tenancy\Database\Drivers\Sqlite\Provider;
use Tenancy\Tests\Database\DatabaseFeatureTestCase;
use Tenancy\Tests\UsesConnections;

class SqliteConnectionDriverTest extends DatabaseFeatureTestCase
{
    use UsesConnections;

    protected $additionalProviders = [Provider::class];

    protected $exception = QueryException::class;

    protected function registerDatabaseListener()
    {
        config(['database.connections.sqlite', include $this->getSqliteConfigurationPath()]);

        $this->configureBoth(function ($event) {
            $event->useConnection('sqlite', [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        });
    }
}
