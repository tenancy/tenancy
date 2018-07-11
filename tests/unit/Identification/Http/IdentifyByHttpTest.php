<?php

namespace Tenancy\Tests\Identification\Drivers\Http;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Http\Providers\IdentificationProvider;
use Tenancy\Tests\Identification\Drivers\Http\Mocks\Tenant;
use Tenancy\Tests\TestCase;

class IdentifyByHttpTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];
    protected $additionalMocks = [__DIR__ . '/Mocks/factories/'];

    /** @var User */
    protected $user;

    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel(Tenant::class);

        $this->tenant = factory(Tenant::class)->create();
    }

    /**
     * @test
     */
    public function request_identifies_tenant()
    {
        $this->assertFalse($this->environment->isIdentified());

        $this->get('/' . $this->tenant->name);

        $this->assertTrue($this->environment->isIdentified());

        $this->assertEquals($this->tenant->name, optional($this->environment->getTenant())->name);
    }

    public function can_register_driver()
    {
        $resolver = $this->app->make(ResolvesTenants::class);

        $resolver->registerDriver(
            IdentifiesByHttp::class,
            'tenantIdentificationByHttp'
        );
    }
}
