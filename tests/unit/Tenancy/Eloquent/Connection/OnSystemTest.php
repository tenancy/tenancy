<?php

namespace Tenancy\Tests\Eloquent\Connection;

use Tenancy\Environment;
use Tenancy\Testing\Mocks\OnSystemModel;
use Tenancy\Testing\TestCase;

class OnSystemTest extends TestCase
{
    /**
     * @test
     */
    public function uses_system_connection()
    {
        $this->assertEquals(
            Environment::getDefaultSystemConnectionName(),
            (new OnSystemModel())->getConnectionName()
        );
    }
}
