<?php

namespace Tenancy\Tests\Database\Mysql\Feature;

use Doctrine\DBAL\Driver\PDOException;
use Tenancy\Database\Drivers\Mysql\Provider;
use Tenancy\Tests\Database\DatabaseFeatureTestCase;
use Tenancy\Tests\Mocks\Tenants\MysqlTenant;
use Tenancy\Tests\UsesConnections;
use Tenancy\Tests\UsesTenants;

class MysqlConnectionDriverTest extends DatabaseFeatureTestCase
{
    use UsesTenants;
    use UsesConnections;

    protected $additionalProviders = [Provider::class];

    protected $exception = PDOException::class;

    protected $tenantModel = MysqlTenant::class;

    protected function afterSetUp()
    {
        $this->registerFactories();
        parent::afterSetUp();
    }

    protected function registerDatabaseListener()
    {
        config(['database.connections.mysql' => include $this->getMysqlConfigurationPath()]);

        $this->configureBoth(function ($event) {
            $event->useConnection('mysql', $event->configuration);
        });
    }
}
