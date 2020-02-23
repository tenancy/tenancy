<?php

namespace Tenancy\Tests\Affects;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

abstract class AffectsIntegrationTestCase extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
        $this->registerAffecting();
    }

    /**
     * Registers the affect functionality in the applicatio.
     *
     * @return void
     */
    abstract protected function registerAffecting();
}
