<?php

namespace Tenancy\Tests\Identification\Drivers\Http;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Environment\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Identification\Drivers\Environment\Mocks\Tenant;

class IdentifyByEnvironmentTest extends TestCase
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

        $this->assertTrue(putenv('TENANT_NAME=' . $this->tenant->name));
        $this->assertEquals($this->tenant->name, env('TENANT_NAME'));

        $this->assertEquals($this->tenant->name, optional($this->environment->getTenant())->name);

        $this->assertTrue($this->environment->isIdentified());
    }
}
