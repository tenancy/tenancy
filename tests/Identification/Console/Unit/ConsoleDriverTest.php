<?php

namespace Tenancy\Identification\Console\Unit;

use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Console\Providers\IdentificationProvider;
use Tenancy\Tests\Identification\DriverTestCase;

class ConsoleDriverTest extends DriverTestCase
{
    protected $provider = IdentificationProvider::class;

    protected $drivers = [
        IdentifiesByConsole::class
    ];
}
