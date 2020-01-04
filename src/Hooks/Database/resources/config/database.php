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
     * Automatic tenant database handling.
     */

    'auto-create' => env('TENANCY_DATABASE_AUTO_CREATE', true),

    'auto-update' => env('TENANCY_DATABASE_AUTO_UPDATE', true),

    'auto-delete' => env('TENANCY_DATABASE_AUTO_DELETE', true),
];
