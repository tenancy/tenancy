<?php

namespace Tenancy\Tests\Affects\Configs\Feature;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Configs\Events\ConfigureConfig;
use Tenancy\Affects\Configs\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureConfigTest extends AffectsEventUnitTestCase
{
    protected $additionalProviders = [Provider::class];

    protected $event = ConfigureConfig::class;

    protected $eventContains = [
        'config' => Repository::class
    ];
}
