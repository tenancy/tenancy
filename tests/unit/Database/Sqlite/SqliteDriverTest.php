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

namespace Tenancy\Tests\Database\Sqlite;

use Tenancy\Database\Drivers\Sqlite\Provider;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Tests\Database\DatabaseDriverTestCase;

class SqliteDriverTest extends DatabaseDriverTestCase
{
    protected $additionalProviders = [Provider::class];

    protected function registerDatabaseListener()
    {
        $this->events->listen(Configuring::class, function (Configuring $event) {
            $event->useConfig(__DIR__.DIRECTORY_SEPARATOR.'database.php', [
                'database' => database_path($event->tenant->getTenantKey().'.sqlite'),
            ]);
        });
    }
}
