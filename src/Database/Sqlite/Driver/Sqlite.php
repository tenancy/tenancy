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
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Identification\Contracts\Tenant;

class Sqlite implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        if ($name = config('tenancy.db-driver-sqlite.use-connection')) {
            return config("database.connections.$name");
        }

        $config = config('tenancy.db-driver-sqlite.preset', []);

        $config['database'] = $tenant->getTenantKey();

        event(new Configuring($tenant, $config, $this));

        return $config;
    }

    public function create(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return touch(database_path($config['database']));
    }

    public function update(Tenant $tenant): bool
    {
        $original = $tenant->newInstance($tenant->getOriginal());

        $previous = $this->configure($original);

        $config = $this->configure($tenant);

        if ($previous['database'] !== $config['database']) {
            return rename(
                database_path($previous['database']),
                database_path($config['database'])
            );
        }

        return false;
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return unlink(database_path($config['database']));
    }
}
