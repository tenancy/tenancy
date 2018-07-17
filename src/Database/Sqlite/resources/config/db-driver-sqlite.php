<?php

return [
    /**
     * If you want to re-use an existing connection,
     * specify its name here. Leave null to
     * instantiate a fully new connection.
     */
    'use-connection' => null,

    'preset' => [
        'driver' => 'sqlite',
        'database' => env('DB_DATABASE', database_path('database.sqlite')),
        'prefix' => '',
    ]
];
