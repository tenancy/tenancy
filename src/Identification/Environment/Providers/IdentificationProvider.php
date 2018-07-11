<?php

namespace Tenancy\Identification\Drivers\Environment\Providers;

use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Identification\Support\DriverProvider;

class IdentificationProvider extends DriverProvider
{
    protected $drivers = [
        IdentifiesByEnvironment::class => 'tenantIdentificationByEnvironment'
    ];
}
