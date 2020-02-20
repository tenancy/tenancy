<?php

namespace Tenancy\Tests\Affects\Views\Unit;

use Illuminate\Contracts\View\Factory;
use Tenancy\Affects\Views\Events\ConfigureViews;
use Tenancy\Affects\Views\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureViewsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureViews::class;

    protected $eventContains = [
        'view' => Factory::class
    ];
}
