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

namespace Tenancy\Tests\Database\Mysql\Feature;

use Doctrine\DBAL\Driver\PDOException;
use Tenancy\Database\Drivers\Mysql\Provider;
use Tenancy\Hooks\Database\Events\Drivers\Configuring;
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
            $event->useConnection('mysql', $event->defaults($event->tenant));

            if ($event instanceof Configuring) {
                $event->configuration['host'] = '%';
            }
        });
    }
}
