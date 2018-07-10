<?php

namespace Tenancy\Tests\Identification\Drivers\Http;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Http\Providers\IdentificationProvider;
use Tenancy\Tests\Identification\Drivers\Http\Mocks\Tenant;
use Tenancy\Tests\TestCase;

class IdentifyByHttpTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];
    protected $additionalMocks = [
        __DIR__ . '/Mocks/factories/'
    ];

    /** @var User */
    protected $user;

    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel(Tenant::class);

        $this->tenant = $this->tenant(Tenant::class, true);
    }

    /**
     * @test
     */
    public function request_identifies_tenant()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $this->assertCount(1, $resolver->getModels());

        $this->assertTrue($this->tenant->exists);

        $this->assertFalse($this->environment->isIdentified());

        $this->get('/' . $this->tenant->name);

        $this->assertTrue($this->environment->isIdentified());

        $this->assertEquals($this->tenant->name, optional($this->environment->getTenant())->name);
    }
}
