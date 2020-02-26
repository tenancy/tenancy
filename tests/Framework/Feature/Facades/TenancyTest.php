<?php

namespace Tenancy\Tests\Framework\Feature\Facades;

use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

class TenanyTest extends TestCase
{
    /** @var Tenant */
    private $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /** @test */
    public function it_proxies_calls_to_the_environment()
    {
        $this->mock(Environment::class, function ($mock){
            $mock
                ->shouldReceive('isIdentified');
        });
        Tenancy::isIdentified();
    }

    /** @test */
    public function it_can_proxy_environment_calls_right()
    {
        $this->assertNull(Tenancy::identifyTenant());

        $this->assertInstanceOf(Environment::class, Tenancy::setTenant($this->tenant));

        $this->assertEquals(
            $this->tenant->name,
            optional(Tenancy::identifyTenant())->name
        );
    }
}
