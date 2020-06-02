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

namespace Tenancy\Database\Drivers\Mysql\Listeners;

use Tenancy\Database\Drivers\Mysql\Driver\Mysql;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Hooks\Database\Events\Resolving;

class ConfiguresTenantDatabase
{
    public function handle(Resolving $resolving): ?ProvidesDatabase
    {
        return new Mysql();
    }
}
