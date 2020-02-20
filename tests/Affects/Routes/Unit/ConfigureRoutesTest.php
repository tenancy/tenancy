<?php

namespace Tenancy\Tests\Affects\Routes\Unit;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use Tenancy\Affects\Routes\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureRoutesTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureRoutes::class;

    protected $eventContains = [
        'router' => Router::class
    ];
}
