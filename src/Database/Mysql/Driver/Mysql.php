<?php

/*
 * This file is part of the tenancy/db-driver-mariadb package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Database\Drivers\Mysql\Driver;

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Identification\Contracts\Tenant;

class Mysql implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        if ($name = config('db-driver-mysql.use-connection')) {
            return config("database.connections.$name");
        }

        $config = config('db-driver-mysql.preset', []);

        $config['database'] = $config['username'] = $tenant->getTenantKey();
        $config['password'] = md5($tenant->getTenantKey() . config('app.key'));

        return $config;
    }

    /**
     * @param Tenant $tenant
     * @return string[] Array of SQL statements.
     */
    public function create(Tenant $tenant): array
    {
        $config = $this->configure($tenant);

        return [
            'user' => "CREATE USER IF NOT EXISTS `{$config['username']}`@'{$config['host']}' IDENTIFIED BY '{$config['password']}'",
            'database' => "CREATE DATABASE `{$config['database']}`",
            'grant' => "GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}' IDENTIFIED BY '{$config['password']}'"
        ];
    }

    /**
     * @param Tenant $tenant
     * @return string[] Array of SQL statements.
     */
    public function update(Tenant $tenant): array
    {
        return [];
    }

    /**
     * @param Tenant $tenant
     * @return string[] Array of SQL statements.
     */
    public function delete(Tenant $tenant): array
    {
        $config = $this->configure($tenant);

        return [
            'user' => "DROP USER `{$config['username']}`@'{$config['host']}'",
            'database' => "DROP DATABASE IF EXISTS `{$config['database']}`"
        ];
    }
}
