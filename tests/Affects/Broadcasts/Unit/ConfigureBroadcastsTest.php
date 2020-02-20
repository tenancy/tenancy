<?php

namespace Tenancy\Tests\Affects\Configs\Unit;

use Tenancy\Affects\Broadcasts\Events\ConfigureBroadcast;
use Tenancy\Affects\Broadcasts\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureBroadcastTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureBroadcast::class;
}
