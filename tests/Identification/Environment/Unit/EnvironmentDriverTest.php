<?php

namespace Tenancy\Identification\Environment\Unit;

use Tenancy\Identification\Drivers\Environment\Providers\IdentificationProvider;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Tests\Identification\DriverTestCase;

class EnvironmentDriverTest extends DriverTestCase
{
    protected $provider = IdentificationProvider::class;

    protected $drivers = [
        IdentifiesByEnvironment::class
    ];
}
