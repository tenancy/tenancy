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

use PDOException;
use Tenancy\Database\Drivers\Mysql\Provider;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Testing\DatabaseDriverTestCase;
use Tenancy\Tests\Database\Mysql\Mocks\Tenant;

class MysqlDriverTest extends DatabaseDriverTestCase
{
    protected $additionalProviders = [Provider::class];

    protected $additionalMocks = [__DIR__.'/Mocks/factories/'];

    protected $tenantModel = Tenant::class;

    protected $exception = PDOException::class;

    protected function registerDatabaseListener()
    {
        config(['database.connections.mysql' => include __DIR__.'/database.php']);
        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConfig(
                __DIR__.DIRECTORY_SEPARATOR.'database.php',
                array_merge($event->configuration, ['allowedHost' => '%']));
        });
    }
}
