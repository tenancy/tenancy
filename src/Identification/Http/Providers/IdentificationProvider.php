<?php

namespace Tenancy\Identification\Drivers\Http\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Tenancy\Identification\Drivers\Http\Listeners\IdentifyByHttp;
use Tenancy\Identification\Events\Resolving;

class IdentificationProvider extends EventServiceProvider
{
    protected $listen = [
        Resolving::class => [
            IdentifyByHttp::class
        ]
    ];
}
