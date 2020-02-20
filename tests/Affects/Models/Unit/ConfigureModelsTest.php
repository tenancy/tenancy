<?php

namespace Tenancy\Tests\Affects\Models\Unit;

use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Affects\Models\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureModelsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureModels::class;
}
