<?php

namespace Tenancy\Tests\Affects\Filesystems\Unit;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Filesystems\Events\ConfigureDisk;
use Tenancy\Affects\Filesystems\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureDiskTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureDisk::class;
}
