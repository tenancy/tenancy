<?php

namespace Tenancy\Identification\Queue\Unit;

use Tenancy\Identification\Drivers\Queue\Providers\IdentificationProvider;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Tests\Identification\DriverTestCase;

class QueueDriverTest extends DriverTestCase
{
    protected $provider = IdentificationProvider::class;

    protected $drivers = [
        IdentifiesByQueue::class
    ];
}
