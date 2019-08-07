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

return [
    /*
     * If you want to re-use an existing connection,
     * specify its name here. Leave null to
     * instantiate a fully new connection.
     */
    'use-connection' => null,

    'preset' => [
        'driver'   => 'sqlite',
        'database' => env('DB_DATABASE', database_path('database.sqlite')),
        'prefix'   => '',
    ],
];
