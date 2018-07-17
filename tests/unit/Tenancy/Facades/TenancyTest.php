<?php

namespace Tenancy\Tests\Facades;

use Tenancy\Environment;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Tests\Mocks\Tenant;
use Tenancy\Tests\TestCase;

class TenancyTest extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $this->tenant = factory(Tenant::class)->make();

        $resolver->addModel(Tenant::class);
    }

    /**
     * @test
     */
    public function can_proxy_environment_calls()
    {
        $this->assertNull(Tenancy::getTenant());

        $this->assertInstanceOf(Environment::class, Tenancy::setTenant($this->tenant));

        $this->assertEquals($this->tenant->name, optional(Tenancy::getTenant())->name);
    }
}
