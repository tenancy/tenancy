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

return [
    'driver'         => 'mysql',
    'host'           => env('TENANCY_MYSQL_HOST', '127.0.0.1'),
    'port'           => env('TENANCY_MYSQL_PORT', '3306'),
    'database'       => env('TENANCY_MYSQL_DB', 'testing'),
    'username'       => env('TENANCY_MYSQL_USERNAME', 'testing'),
    'password'       => env('TENANCY_MYSQL_PASSWORD', ''),
    'unix_socket'    => env('DB_SOCKET', ''),
    'charset'        => 'utf8mb4',
    'collation'      => 'utf8mb4_unicode_ci',
    'prefix'         => '',
    'prefix_indexes' => true,
    'strict'         => true,
    'engine'         => null,
    'options'        => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
];
