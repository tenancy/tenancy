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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Tenancy\Database\Drivers\Sqlite\Provider;
use Tenancy\Tests\Database\DatabaseFeatureTestCase;
use Tenancy\Tests\UsesConnections;

class SqliteConnectionDriverTest extends DatabaseFeatureTestCase
{
    use UsesConnections;

    protected array $additionalProviders = [Provider::class];

    protected $exception = QueryException::class;

    protected function registerDatabaseListener()
    {
        if (Str::startsWith(App::version(), '11') || Str::startsWith(App::version(), '12')) {
            $this->exception = \Illuminate\Database\SQLiteDatabaseDoesNotExistException::class;
        }

        config(['database.connections.sqlite', include $this->getSqliteConfigurationPath()]);

        $this->configureBoth(function ($event) {
            $event->useConnection('sqlite', [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        });
    }
}
