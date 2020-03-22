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

namespace Tenancy\Database\Drivers\Postgres\Driver;

use Closure;
use Illuminate\Support\Facades\DB;
use Tenancy\Identification\Contracts\Tenant;
use Illuminate\Database\ConnectionInterface;
use Tenancy\Hooks\Database\Events\Drivers as Events;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Drivers\Postgres\Concerns\ManagesSystemConnection;

class Postgres implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        $config = [];

        event(new Events\Configuring($tenant, $config, $this));

        return $config;
    }

    public function create(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Creating($tenant, $config, $this));

        return $this->processAndDispatch(Events\Created::class, $tenant, function (ConnectionInterface $db) use ($config) {
            return $db->unprepared("CREATE USER \"{$config['username']}\" WITH PASSWORD '{$config['password']}'") &&
                $db->unprepared("CREATE DATABASE \"{$config['database']}\" WITH OWNER \"{$config['username']}\"");
        });
    }

    public function update(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Updating($tenant, $config, $this));

        if (!isset($config['oldUsername'])) {
            return false;
        }

        return $this->processAndDispatch(Events\Updated::class, $tenant, function (ConnectionInterface $db) use ($config) {
            return $db->unprepared("ALTER USER \"{$config['oldUsername']}\" RENAME TO \"{$config['username']}\"") &&
                $db->unprepared("ALTER USER \"{$config['username']}\" PASSWORD '{$config['password']}'") &&
                $db->unprepared("ALTER DATABASE \"{$config['oldUsername']}\" RENAME TO \"{$config['database']}\"");
        });
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Deleting($tenant, $config, $this));

        return $this->processAndDispatch(Events\Deleted::class, $tenant, function (ConnectionInterface $db) use ($config) {
            return $db->unprepared(sprintf('DROP DATABASE IF EXISTS "%s"', $config['database'])) &&
                $db->unprepared(sprintf('DROP USER "%s"', $config['username']));
        });
    }

    /**
     * Get the system database connection.
     *
     * @param  Tenant  $tenant
     * @return ConnectionInterface
     */
    protected function system(Tenant $tenant): ConnectionInterface
    {
        $connection = null;

        if ($tenant instanceof ManagesSystemConnection) {
            $connection = $tenant->getManagingSystemConnection() ?? $connection;
        }

        return DB::connection($connection);
    }

    /**
     * Processes the provided statements and dispatches an event.
     *
     * @param string $event
     * @param Tenant $tenant
     * @param Closure $callback
     *
     * @return bool
     */
    private function processAndDispatch(string $event, Tenant $tenant, Closure $callback)
    {
        $result = $callback($this->system($tenant));

        event((new $event($tenant, $this, $result)));

        return $result;
    }
}
