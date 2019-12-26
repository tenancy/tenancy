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

namespace Tenancy\Tests\Database\Mysql;

use PDOException;
use Tenancy\Database\Drivers\Mysql\Provider;
use Tenancy\Hooks\Database\Events\Drivers\Creating;
use Tenancy\Hooks\Database\Events\Drivers\Deleting;
use Tenancy\Hooks\Database\Events\Drivers\Updating;
use Tenancy\Tests\Database\Mysql\Mocks\Tenant;
use Tenancy\Tests\Hooks\Database\DatabaseDriverTestCase;

class MysqlDriverTest extends DatabaseDriverTestCase
{
    protected $additionalProviders = [Provider::class];

    protected $additionalMocks = [__DIR__.'/Mocks/factories/', __DIR__.'/../../Hooks/Database/Mocks/factories'];

    protected $tenantModel = Tenant::class;

    protected $exception = PDOException::class;

    protected function registerDatabaseListener()
    {
        config(['database.connections.mysql' => include __DIR__.'/database.php']);

        $this->configureBoth(function ($event) {
            $event->useConfig(
                __DIR__.DIRECTORY_SEPARATOR.'database.php',
                $event->configuration);
        });

        $this->events->listen([Creating::class, Updating::class, Deleting::class], function ($event) {
            $event->configuration['host'] = '%';
        });
    }
}
