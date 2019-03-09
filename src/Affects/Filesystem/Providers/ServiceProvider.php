<?php

namespace Tenancy\Affects\Filesystem\Providers;

use Tenancy\Affects\Filesystem\Listeners\ConfiguresDisk;
use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Support\DriverProvider;

class ServiceProvider extends DriverProvider
{
    protected $listen = [
        Resolved::class => [
            ConfiguresDisk::class
        ]
    ];
}
