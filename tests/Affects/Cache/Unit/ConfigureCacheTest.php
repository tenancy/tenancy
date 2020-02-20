<?php

namespace Tenancy\Tests\Affects\Cache\Unit;

use Tenancy\Affects\Cache\Events\ConfigureCache;
use Tenancy\Affects\Cache\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureCacheTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureCache::class;
}
