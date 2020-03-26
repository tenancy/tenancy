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

namespace Tenancy\Database\Drivers\Pgsql\Driver;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Tenancy\Database\Drivers\Pgsql\Concerns\ManagesSystemConnection;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Hooks\Database\Events\Drivers as Events;
use Tenancy\Hooks\Database\Support\QueryManager;
use Tenancy\Identification\Contracts\Tenant;

class Pgsql implements ProvidesDatabase
{
    protected $queryManager;

    public function __construct()
    {
        $this->queryManager = App::make(QueryManager::class);
    }

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

        $connection = $this->system($tenant);

        $result = $this->queryManager->setConnection($connection)
                     ->process(function () use ($config) {
                         $this->statement("CREATE USER \"{$config['username']}\" WITH PASSWORD '{$config['password']}'");
                         $this->statement("CREATE DATABASE \"{$config['database']}\" WITH OWNER = \"{$config['username']}\"");
                     })
                     ->getStatus();

        event(new Events\Created($tenant, $this, $result));

        return $result;
    }

    public function update(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Updating($tenant, $config, $this));

        if (!isset($config['oldUsername'])) {
            return false;
        }

        $connection = $this->system($tenant);

        $result = $this->queryManager->setConnection($connection)
                     ->processTransaction(function () use ($config) {
                         $this->statement("ALTER USER \"{$config['oldUsername']}\" RENAME TO \"{$config['username']}\"");
                         $this->statement("ALTER USER \"{$config['username']}\" WITH PASSWORD '{$config['password']}'");
                     })
                     ->process(function () use ($config) {
                         $this->statement("CREATE DATABASE \"{$config['database']}\" WITH OWNER = \"{$config['username']}\" TEMPLATE = \"{$config['oldUsername']}\"");
                         // Add database drop statement as last statement
                         $this->statement("DROP DATABASE \"{$config['oldUsername']}\"");
                     })
                     ->getStatus();

        event(new Events\Updated($tenant, $this, $result));

        return $result;
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Deleting($tenant, $config, $this));

        $result = $this->queryManager->setConnection($this->system($tenant))
            ->process(function () use ($config) {
                $this->statement("DROP DATABASE \"{$config['database']}\"");
                $this->statement("DROP USER \"{$config['username']}\"");
            })
            ->getStatus();

        event(new Events\Deleted($tenant, $this, $result));

        return $result;
    }

    protected function system(Tenant $tenant): ConnectionInterface
    {
        $connection = null;

        if (in_array(ManagesSystemConnection::class, class_implements($tenant))) {
            $connection = $tenant->getManagingSystemConnection() ?? $connection;
        }

        return DB::connection($connection);
    }
}
