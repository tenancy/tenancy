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
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

return [
    'identification' => [

        /*
         * Whether to initiate tenant identification early.
         *
         * @info This will set up a middleware with high priority to
         * resolve the Environment and run the tenant identification.
         *
         * @var bool
         */
        'eager' => env('TENANCY_EAGER_IDENTIFICATION', true),
    ],
    'database' => [
        /*
         * The name of the tenant connection, tenancy will create this connection during runtime.
         */
        'tenant-connection-name' => env('TENANCY_TENANT_CONNECTION_NAME', 'tenant'),

        /*
         * Automatic tenant database handling.
         */

        'auto-create' => env('TENANCY_DATABASE_AUTO_CREATE', true),

        'auto-update' => env('TENANCY_DATABASE_AUTO_UPDATE', true),

        'auto-delete' => env('TENANCY_DATABASE_AUTO_DELETE', true),
    ],
];
