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

namespace Tenancy\Database\Drivers\Mysql;

use Tenancy\Database\Drivers\Mysql\Listeners\ConfiguresTenantDatabase;
use Tenancy\Hooks\Database\Support\DatabaseProvider;

class Provider extends DatabaseProvider
{
    protected $listener = ConfiguresTenantDatabase::class;
}
