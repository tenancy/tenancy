<?php

use Tenancy\SDK\Identification\HTTP\Mode as HTTP;
use Tenancy\SDK\Identification\Console\Mode as Console;
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
             * The mode for console identification
             */
            'mode' => Console::DUMP,
        ],
        'queue' => [
            'mode' => Queue::DUMP,
        ]
    ]
];
