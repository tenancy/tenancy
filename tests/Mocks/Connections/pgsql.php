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
    'driver'         => 'pgsql',
    'host'           => env('TENANCY_HOST', '127.0.0.1'),
    'port'           => env('DB_PORT', '5432'),
    'database'       => env('TENANCY_DB', 'testing'),
    'username'       => env('TENANCY_USERNAME', 'testing'),
    'password'       => env('TENANCY_PASSWORD', ''),
    'charset'        => 'utf8',
    'prefix'         => '',
    'prefix_indexes' => true,
    'schema'         => 'public',
    'sslmode'        => 'prefer',
];
