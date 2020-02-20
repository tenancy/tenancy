<?php

namespace Tenancy\Tests\Affects\URLs\Unit;

use Illuminate\Contracts\Routing\UrlGenerator;
use Tenancy\Affects\URLs\Events\ConfigureURL;
use Tenancy\Affects\URLs\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureURLTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureURL::class;

    protected $eventContains = [
        'url' => UrlGenerator::class
    ];
}
