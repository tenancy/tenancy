<?php declare(strict_types=1);

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
    'identification' => [

        /**
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
        /**
         * The name of the tenant connection, tenancy will create this connection during runtime.
         */
        'tenant-connection-name' => env('TENANCY_TENANT_CONNECTION_NAME', 'tenant'),

        /**
         * By default we'll use the default connection defined in your database.php as a global database.
         * There you can store tables that you like to share between your tenants. In case you wish
         * to override this connection please specify.
         *
         * @info if set to null will use the default connection (mysql).
         */
        'system-connection-name' => env('TENANCY_SYSTEM_CONNECTION_NAME', null),

        /**
         * Enabling this setting will force all Eloquent models to use the tenant connection by default.
         */
        'models-default-to-tenant-connection' => env('TENANCY_ELOQUENT_USES_TENANT_CONNECTION', false),

        /**
         * Enabling this setting will force all Eloquent models to use the system connection by default.
         */
        'models-default-to-system-connection' => env('TENANCY_ELOQUENT_USES_SYSTEM_CONNECTION', false),

        /**
         * Automatic tenant database handling.
         */

        'auto-create' => env('TENANCY_DATABASE_AUTO_CREATE', true),

        'auto-update' => env('TENANCY_DATABASE_AUTO_UPDATE', true),

        'auto-delete' => env('TENANCY_DATABASE_AUTO_DELETE', true),
    ]
];
