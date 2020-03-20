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

namespace Tenancy\Tests\Database\Sqlite\Feature;

use Illuminate\Database\QueryException;
use Tenancy\Database\Drivers\Sqlite\Provider;
use Tenancy\Tests\Database\DatabaseFeatureTestCase;
use Tenancy\Tests\UsesConnections;

class SqliteConfigDriverTest extends DatabaseFeatureTestCase
{
    use UsesConnections;

    protected $additionalProviders = [Provider::class];

    protected $exception = QueryException::class;

    protected function registerDatabaseListener()
    {
        $this->configureBoth(function ($event) {
            $event->useConfig($this->getSqliteConfigurationPath(), [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        });
    }
}
