<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database\Postgres\Feature;

use Doctrine\DBAL\Driver\PDOException;
use Tenancy\Database\Drivers\Postgres\Provider;
use Tenancy\Tests\Mocks\Tenants\PostgresTenant;
use Tenancy\Tests\Database\DatabaseFeatureTestCase;
use Tenancy\Tests\UsesConnections;
use Tenancy\Tests\UsesTenants;

class PostgresConnectionDriverTest extends DatabaseFeatureTestCase
{
    use UsesTenants;
    use UsesConnections;

    protected $additionalProviders = [Provider::class];

    protected $exception = PDOException::class;

    protected $tenantModel = PostgresTenant::class;

    protected function afterSetUp()
    {
        $this->registerFactories();
        parent::afterSetUp();
    }

    protected function registerDatabaseListener()
    {
        config(['database.connections.pgsql' => include $this->getPostgresConfigurationPath()]);

        $this->configureBoth(function ($event) {
            $event->useConnection('pgsql', $event->configuration);
        });
    }
}
