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

namespace Tenancy\Tests\Database\Pgsql\Feature;

use Doctrine\DBAL\Driver\PDOException;
use Tenancy\Database\Drivers\Pgsql\Provider;
use Tenancy\Tests\Database\DatabaseFeatureTestCase;
use Tenancy\Tests\Mocks\Tenants\PgsqlTenant;
use Tenancy\Tests\UsesConnections;
use Tenancy\Tests\UsesTenants;

class PgsqlConnectionDriverTest extends DatabaseFeatureTestCase
{
    use UsesTenants;
    use UsesConnections;

    protected $additionalProviders = [Provider::class];

    protected $exception = PDOException::class;

    protected $tenantModel = PgsqlTenant::class;

    protected function afterSetUp()
    {
        $this->registerFactories();
        parent::afterSetUp();
    }

    protected function registerDatabaseListener()
    {
        config(['database.connections.pgsql' => include $this->getPgsqlConfigurationPath()]);

        $this->configureBoth(function ($event) {
            $event->useConnection('pgsql', $event->defaults($event->tenant));
        });
    }
}
