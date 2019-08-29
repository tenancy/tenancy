<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Database\Drivers\Mysql\Driver;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Hooks\Database\Events\Drivers as Events;
use Tenancy\Identification\Contracts\Tenant;

class Mysql implements ProvidesDatabase
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

        return $this->process($tenant, [
            'user'     => "CREATE USER IF NOT EXISTS `{$config['username']}`@'{$config['host']}' IDENTIFIED BY '{$config['password']}'",
            'database' => "CREATE DATABASE `{$config['database']}`",
            'grant'    => "GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}'",
        ]);
    }

    public function update(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Updating($tenant, $config, $this));

        if (!isset($config['oldUsername'])) {
            return false;
        }

        $tempTenant = $tenant;
        $tempTenant->{$tempTenant->getTenantKeyName()} = $tenant->getOriginal($tenant->getTenantKeyName());
        $originalTenant = Tenancy::getTenant();

        $resolver = resolve(\Tenancy\Affects\Connection\ConnectionResolver::class);
        $resolver->__invoke($tempTenant, Tenancy::getTenantConnectionName());
        $connection = Tenancy::getTenantConnection();
        $tables = $connection->getDoctrineSchemaManager()->listTableNames();

        Tenancy::setTenant($originalTenant);

        $tableStatements = [];
        foreach ($tables as $table) {
            $tableStatements['table'.$table] = "RENAME TABLE `{$config['oldUsername']}`.{$table} TO `{$config['database']}`.{$table}";
        }

        $statements = array_merge([
            'user'     => "RENAME USER `{$config['oldUsername']}`@'{$config['host']}' TO `{$config['username']}`@'{$config['host']}'",
            'password' => "ALTER USER `{$config['username']}`@`{$config['host']}` IDENTIFIED BY '{$config['password']}'",
            'database' => "CREATE DATABASE `{$config['database']}`",
            'grant'    => "GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}'",
        ], $tableStatements);

        // Add database drop statement as last statement
        $tableStatements['delete_db'] = "DROP DATABASE `{$config['oldUsername']}`";

        return $this->process($tenant, $statements);
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        event(new Events\Deleting($tenant, $config, $this));

        return $this->process($tenant, [
            'user'     => "DROP USER `{$config['username']}`@'{$config['host']}'",
            'database' => "DROP DATABASE IF EXISTS `{$config['database']}`",
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
                $success = $this->system($tenant)->statement($statement);

                if (!$success) {
                    throw new QueryException($statement, [], null);
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
