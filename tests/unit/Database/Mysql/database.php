<?php

declare(strict_types=1);

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

return [
    'driver'         => 'mysql',
    'host'           => env('DB_HOST', '127.0.0.1'),
    'port'           => env('DB_PORT', '3306'),
    'database'       => env('DB_DATABASE', 'forge'),
    'username'       => env('DB_USERNAME', 'forge'),
    'password'       => env('DB_PASSWORD', ''),
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
