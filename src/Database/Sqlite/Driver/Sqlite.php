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

namespace Tenancy\Database\Drivers\Sqlite\Driver;

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Identification\Contracts\Tenant;

class Sqlite implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        if ($name = config('db-driver-sqlite.use-connection')) {
            return config("database.connections.$name");
        }

        $config = config('db-driver-sqlite.preset', []);

        $config['database'] = $tenant->getTenantKey();

        return $config;
    }

    /**
     * @param Tenant $tenant
     * @return string[] Array of SQL statements.
     */
    public function create(Tenant $tenant): array
    {
        $config = $this->configure($tenant);

        touch(database_path($config['database']));

        return [];
    }

    /**
     * @param Tenant $tenant
     * @return string[] Array of SQL statements.
     */
    public function update(Tenant $tenant): array
    {
        $original = $tenant->newInstance($tenant->getOriginal());

        $previous = $this->configure($original);

        $config = $this->configure($tenant);

        if ($previous['database'] !== $config['database']) {
            rename(
                database_path($previous['database']),
                database_path($config['database'])
            );
        }

        return [];
    }

    /**
     * @param Tenant $tenant
     * @return string[] Array of SQL statements.
     */
    public function delete(Tenant $tenant): array
    {
        $config = $this->configure($tenant);

        unlink(database_path($config['database']));

        return [];
    }
}
