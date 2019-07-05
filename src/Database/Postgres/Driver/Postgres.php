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
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Database\Drivers\Postgres\Driver;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Drivers\Postgres\Concerns\ManagesSystemConnection;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Identification\Contracts\Tenant;

class Postgres implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        $config = [];

        event(new Configuring($tenant, $config, $this));

        return $config;
    }

    public function create(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return $this->process($tenant, [
            'user'     => ['CREATE USER ? WITH ENCRYPTED PASSWORD ?', [$config['username'], $config['password']]],
            'database' => ['CREATE DATABASE ?', [$config['database']]],
            'grant'    => ['GRANT ALL PRIVILEGES ON DATABASE ? TO ?', [$config['database'], $config['username']]],
        ]);
    }

    public function update(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        if (!isset($config['oldUsername'])) {
            return false;
        }

        return $this->process($tenant, [
            'user' => ['RENAME USER ? TO ?', [$config['oldUsername'], $config['username']]],
        ]);
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return $this->process($tenant, [
            'user'     => ['DROP USER ?', [$config['username']]],
            'database' => ['DROP DATABASE IF EXISTS ?', [$config['database']]],
        ]);
    }

    protected function system(Tenant $tenant): ConnectionInterface
    {
        $connection = null;

        if (in_array(ManagesSystemConnection::class, class_implements($tenant))) {
            $connection = $tenant->getManagingSystemConnection() ?? $connection;
        }

        return DB::connection($connection);
    }

    protected function process(Tenant $tenant, array $statements): bool
    {
        $success = false;

        $this->system($tenant)->beginTransaction();

        foreach ($statements as $statement) {
            try {
                if (!is_array($statement)) {
                    $statement = [$statement, []];
                }

                // execute statement with bindings
                $success = call_user_func_array($this->system($tenant)->statement, $statement);

                if (!$success) {
                    throw new QueryException($statement[0], $statement[1]);
                }
            } catch (QueryException $e) {
                report($e);

                $this->system($tenant)->rollBack();
            }
        }

        $this->system($tenant)->commit();

        return $success;
    }
}
