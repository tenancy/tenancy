<?php

namespace Tenancy\Tests\Affects;

use Tenancy\Testing\TestCase;

abstract class AffectsIntegrationTest extends TestCase
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
