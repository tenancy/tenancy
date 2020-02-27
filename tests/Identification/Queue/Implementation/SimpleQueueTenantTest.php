<?php

namespace Tenancy\Tests\Identification\Queue\Implementation;

use Illuminate\Support\Facades\Event;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Queue\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Jobs\OverrideableJob;
use Tenancy\Tests\Mocks\Jobs\OverrideableKeysJob;
use Tenancy\Tests\Mocks\Jobs\SimpleJob;
use Tenancy\Tests\Mocks\Tenants\SimpleQueueTenant;

class SimpleQueueTenantTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    protected function afterSetUp()
    {
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel(SimpleQueueTenant::class);
    }

    /** @test */
    public function it_identifies_a_tenant()
    {
        $tenant = $this->createMockTenant();

        $this->environment->setTenant($tenant);

        Event::listen('mock.tenant.job', function ($event) use ($tenant) {
            $this->assertEquals(
                $tenant->getTenantKey(),
                $event->getTenantKey()
            );
        });

        dispatch(new SimpleJob());
    }

    /** @test */
    public function it_identifies_an_override_tenant()
    {
        $tenant = $this->createMockTenant();

        $this->environment->setTenant($tenant);

        $override = $this->createMockTenant();

        Event::listen('mock.tenant.job', function ($event) use ($override) {
            $this->assertEquals(
                $override->getTenantKey(),
                $event->getTenantKey()
            );
        });

        dispatch(new OverrideableJob($override));
    }

    /** @test */
    public function it_identifies_an_override_tenant_on_keys()
    {
        $tenant = $this->createMockTenant();

        $this->environment->setTenant($tenant);

        $override = $this->createMockTenant();

        Event::listen('mock.tenant.job', function ($event) use ($override) {
            $this->assertEquals(
                $override->getTenantKey(),
                $event->getTenantKey()
            );
        });

        dispatch(new OverrideableKeysJob($override));
    }
}
