<?php

namespace Tenancy\Tests\Affects\Cache\Feature;

use Illuminate\Cache\FileStore;
use Tenancy\Tests\Affects\Cache\UsesFileDriver;

class ConfigureCacheFileTest extends DriverTest
{
    use UsesFileDriver;

    /** @var string */
    protected $storeClass = FileStore::class;
}
