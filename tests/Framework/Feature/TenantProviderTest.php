<?php

namespace Tenancy\Tests\Framework\Feature;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

class TenantProviderTest extends TestCase
{
    /** @test */
    public function it_can_resolve_null()
    {
        $this->assertNull(resolve(Tenant::class));
    }

    /** @test */
    public function it_can_resolve_the_tenant_from_the_environment()
    {
        $tenant = $this->mockTenant();
        $this->environment->setTenant($tenant);

        $this->assertEquals(
            $tenant,
            resolve(Tenant::class)
        );
    }
}
