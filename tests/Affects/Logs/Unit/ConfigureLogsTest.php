<?php

namespace Tenancy\Tests\Affects\Logs\Unit;

use Tenancy\Affects\Logs\Events\ConfigureLogs;
use Tenancy\Affects\Logs\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureLogsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureLogs::class;
}
