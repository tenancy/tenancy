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
    /*
     * The name of the tenant connection, tenancy will create this connection during runtime.
     */
    'tenant-connection-name' => env('TENANCY_TENANT_CONNECTION_NAME', 'tenant'),
];
