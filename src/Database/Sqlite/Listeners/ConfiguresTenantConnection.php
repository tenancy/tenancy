<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Database\Drivers\Sqlite\Listeners;

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Drivers\Sqlite\Driver\Sqlite;
use Tenancy\Database\Events\Resolving;

class ConfiguresTenantConnection
{
    public function handle(Resolving $resolving): ?ProvidesDatabase
    {
        return new Sqlite();
    }
}
