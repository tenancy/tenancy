<?php

namespace Tenancy\Identification\Drivers\Console\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Tenancy\Identification\Drivers\Console\Listeners\IdentifyByConsole;
use Tenancy\Identification\Drivers\Console\Middleware\EagerIdentification;
use Tenancy\Identification\Events\Resolving;

class IdentificationProvider extends EventServiceProvider
{
    protected $listen = [
        CommandStarting::class => [
            EagerIdentification::class
        ],
        Resolving::class => [
            IdentifyByConsole::class
        ]
    ];
}
