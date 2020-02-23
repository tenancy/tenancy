<?php

namespace Tenancy\Tests\Identification\Http\Unit;

use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Http\Providers\IdentificationProvider;
use Tenancy\Tests\Identification\DriverTestCase;

class HttpDriverTest extends DriverTestCase
{
    protected $provider = IdentificationProvider::class;

    protected $drivers = [
        IdentifiesByHttp::class
    ];
}
