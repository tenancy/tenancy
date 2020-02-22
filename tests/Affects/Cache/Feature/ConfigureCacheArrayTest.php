<?php

namespace Tenancy\Tests\Affects\Cache\Feature;

use Illuminate\Cache\ArrayStore;
use Tenancy\Tests\Affects\Cache\UsesArrayDriver;

class ConfigureCacheArrayTest extends DriverTest
{
    use UsesArrayDriver;

    /** @var string */
    protected $storeClass = ArrayStore::class;
}
