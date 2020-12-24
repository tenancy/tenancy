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

namespace Tenancy\Database\Drivers\Mysql\Driver;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Tenancy\Affects\Connections\Contracts\ResolvesConnections;
use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Hooks\Database\Events\Drivers as Events;
use Tenancy\Hooks\Database\Support\QueryManager;
use Tenancy\Identification\Contracts\Tenant;

class Mysql implements ProvidesDatabase
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

        $result = $this->queryManager->setConnection($this->system($tenant))
            ->process(function () use ($config) {
                $this->statement("CREATE USER IF NOT EXISTS `{$config['username']}`@'{$config['host']}' IDENTIFIED BY '{$config['password']}'");
                $this->statement("CREATE DATABASE `{$config['database']}`");
                $this->statement("GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}'");
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

        $tables = $this->retrieveTables($tenant);

        $result = $this->queryManager->setConnection($this->system($tenant))
            ->process(function () use ($config, $tables) {
                $this->statement("RENAME USER `{$config['oldUsername']}`@'{$config['host']}' TO `{$config['username']}`@'{$config['host']}'");
                $this->statement("ALTER USER `{$config['username']}`@`{$config['host']}` IDENTIFIED BY '{$config['password']}'");
                $this->statement("CREATE DATABASE `{$config['database']}`");
                $this->statement("GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}'");

                foreach ($tables as $table) {
                    $this->statement("RENAME TABLE `{$config['oldUsername']}`.{$table} TO `{$config['database']}`.{$table}");
                }

                // Add database drop statement as last statement
                $this->statement("DROP DATABASE `{$config['oldUsername']}`");
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
                $this->statement("DROP USER `{$config['username']}`@'{$config['host']}'");
                $this->statement("DROP DATABASE IF EXISTS `{$config['database']}`");
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

    /**
     * @param Tenant $tenant
     *
     * @return array
     */
    protected function retrieveTables(Tenant $tenant): array
    {
        $tempTenant = $tenant->replicate();
        $tempTenant->{$tenant->getTenantKeyName()} = $tenant->getOriginal($tenant->getTenantKeyName());

        /** @var ResolvesConnections $resolver */
        $resolver = resolve(ResolvesConnections::class);
        $resolver($tempTenant, Tenancy::getTenantConnectionName());

        $tables = Tenancy::getTenantConnection()->getDoctrineSchemaManager()->listTableNames();

        $resolver(null, Tenancy::getTenantConnectionName());

        return $tables;
    }
}
