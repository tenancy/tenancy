<?php

namespace Tenancy\Tests\Affects\Mails\Unit;

use Tenancy\Affects\Mails\Events\ConfigureMails;
use Tenancy\Affects\Mails\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureMailsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureMails::class;
}
