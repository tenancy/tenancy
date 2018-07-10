<?php

namespace Tenancy\Identification\Drivers\Environment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Tenancy\Identification\Drivers\Environment\Listeners\IdentifyByEnvironment;
use Tenancy\Identification\Events\Resolving;

class IdentificationProvider extends EventServiceProvider
{
    protected $listen = [
        Resolving::class => [
            IdentifyByEnvironment::class
        ]
    ];
}
