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

namespace Tenancy\Database\Drivers\Sqlite;

use Tenancy\Database\Drivers\Sqlite\Listeners\ConfiguresTenantConnection;
use Tenancy\Support\DatabaseProvider;

class Provider extends DatabaseProvider
{
    protected $listener = ConfiguresTenantConnection::class;

    protected $configs = [
        __DIR__.'/resources/config/db-driver-sqlite.php',
    ];
}
