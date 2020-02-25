<?php

namespace Tenancy\Tests\Framework\Feature;

use Tenancy\Testing\TestCase;

class EnvironmentTest extends TestCase
{
    /** @test */
    public function it_returns_null_when_no_tenant_identified()
    {
        $this->assertNull($this->environment->getTenant());
    }

    /** @test */
    public function error_on_wrong_object()
    {
        $tenant = new \stdClass();

        $this->expectException(\TypeError::class);

        $this->environment->setTenant($tenant);
    }

    /** @test */
    public function prefers_identified_tenant()
    {
        $tenant = $this->mockTenant();
        $newTenant = $this->mockTenant();

        $this->resolveTenant($newTenant);
        $this->environment->setTenant($tenant);

        $this->assertEquals(
            $tenant,
            $this->environment->getTenant()
        );
    }
}
