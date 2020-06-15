<?php

use Tenancy\SDK\Identification\HTTP\Mode as HTTP;
use Tenancy\SDK\Identification\Console\Mode as Console;

return [
    'identification' => [
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
        ]
    ]
];
