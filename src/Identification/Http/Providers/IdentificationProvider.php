<?php

namespace Tenancy\Identification\Drivers\Http\Providers;

use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Support\DriverProvider;

class IdentificationProvider extends DriverProvider
{
    protected $drivers = [
        IdentifiesByHttp::class => 'tenantIdentificationByHttp'
    ];
}
