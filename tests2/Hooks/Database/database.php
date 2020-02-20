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
    'driver'                  => 'sqlite',
    'url'                     => env('DATABASE_URL'),
    'database'                => env('DB_DATABASE', database_path('database.sqlite')),
    'prefix'                  => '',
    'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
];
