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

use Tenancy\SDK\Identification\Console\Mode as Console;
use Tenancy\SDK\Identification\HTTP\Mode as HTTP;
use Tenancy\SDK\Identification\Queue\Mode as Queue;

return [
    'identification' => [
        'tenants' => [
            // \App\Tenant::class,
        ],
        'http' => [
            /**
             * The mode used for HTTP identification.
             */
            'mode' => HTTP::DUMP,
        ],
        'console' => [
            /**
             * The mode for console identification.
             */
            'mode' => Console::DUMP,
        ],
        'queue' => [
            'mode' => Queue::DUMP,
        ],
    ],
];
