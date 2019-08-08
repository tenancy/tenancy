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
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Database\Drivers\Mysql\Driver;

use Tenancy\Facades\Tenancy;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\ConnectionInterface;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;

class Mysql implements ProvidesDatabase
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

        if (isset($config['allowedHost'])) {
            $config['host'] = $config['allowedHost'];
        }

        return $this->process($tenant, [
            'user'     => "CREATE USER IF NOT EXISTS `{$config['username']}`@'{$config['host']}' IDENTIFIED BY '{$config['password']}'",
            'database' => "CREATE DATABASE `{$config['database']}`",
            'grant'    => "GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}'",
        ]);
    }

    public function update(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        if (!isset($config['oldUsername'])) {
            return false;
        }

        if (isset($config['allowedHost'])) {
            $config['host'] = $config['allowedHost'];
        }

        $tempTenant = $tenant;
        $tempTenant->{$tempTenant->getTenantKeyName()} = $tenant->getOriginal($tenant->getTenantKeyName());
        $originalTenant = Tenancy::getTenant();

        Tenancy::setTenant($tempTenant);
        $connection = Tenancy::getTenantConnection();
        $tables = $connection->select('SHOW TABLES');

        $tableStatements = [];
        foreach($tables as $table){
            foreach($table as $key => $value){
                $tableStatements['table'.$value] = "RENAME TABLE `{$config['oldUsername']}`.{$value} TO `{$config['database']}`.{$value}";
            }
        }

        // Add database drop statement as last statement
        $tableStatements['delete_db'] = "DROP DATABASE `{$config['oldUsername']}`";

        $statements = array_merge([
            'user'     => "RENAME USER `{$config['oldUsername']}`@'{$config['host']}' TO `{$config['username']}`@'{$config['host']}'",
            'password' => "ALTER USER `{$config['username']}`@`{$config['host']}` IDENTIFIED BY '{$config['password']}'",
            'database' => "CREATE DATABASE `{$config['database']}`",
            'grant'    => "GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}'",
        ], $tableStatements);

        Tenancy::setTenant($originalTenant);

        return $this->process($tenant, $statements);
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        if (isset($config['allowedHost'])) {
            $config['host'] = $config['allowedHost'];
        }

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
