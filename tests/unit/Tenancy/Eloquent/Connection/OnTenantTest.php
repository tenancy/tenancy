<?php

namespace Tenancy\Tests\Eloquent\Connection;

use Tenancy\Environment;
use Tenancy\Testing\Mocks\OnTenantModel;
use Tenancy\Testing\TestCase;

class OnTenantTest extends TestCase
{
    /**
     * @test
     */
    public function uses_system_connection()
    {
        $this->assertEquals(
            Environment::getTenantConnectionName(),
            (new OnTenantModel())->getConnectionName()
        );
    }
}
