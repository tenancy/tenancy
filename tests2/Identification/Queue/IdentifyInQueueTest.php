<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Identification\Queue;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Queue\Providers\IdentificationProvider;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Identification\Queue\Mocks\TenantIdentifiableByQueue;

class IdentifyInQueueTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];
    protected $additionalMocks = [__DIR__.'/Mocks/factories/'];

    /** @var ResolvesTenants */
    protected $resolver;

    protected function afterSetUp()
    {
        /* @var ResolvesTenants $resolver */
        $this->resolver = $this->app->make(ResolvesTenants::class);
        $this->resolver->addModel(Tenant::class);
        $this->resolver->addModel(TenantIdentifiableByQueue::class);
    }

    /**
     * @test
     */
    public function queue_identifies_tenant()
    {
        $tenant = factory(TenantIdentifiableByQueue::class)->create();

        $this->environment->setTenant($tenant);

        Event::listen([JobProcessed::class, JobProcessing::class], function ($event) use ($tenant) {
            $payload = json_decode($event->job->getRawBody(), true);

            $this->assertEquals($tenant->getTenantIdentifier(), $payload['tenant_identifier']);
            $this->assertEquals($tenant->getTenantKey(), $payload['tenant_key']);
        });

        Event::listen('mock.tenant.job', function ($event) use ($tenant) {
            $this->assertEquals($tenant->getTenantIdentifier(), $event->getTenantIdentifier());
            $this->assertEquals($tenant->getTenantKey(), $event->getTenantKey());
        });

        dispatch(new Mocks\Job());
    }

    /**
     * @test
     */
    public function queue_identifies_tenant_as_model()
    {
        $this->environment->setTenant($this->createMockTenant());

        $override = factory(TenantIdentifiableByQueue::class)->create();

        Event::listen('mock.tenant.job', function ($event) use ($override) {
            $this->assertEquals($override->getTenantIdentifier(), $event->getTenantIdentifier());
            $this->assertEquals($override->getTenantKey(), $event->getTenantKey());
        });

        dispatch(new Mocks\Job(null, null, $override));
    }

    /**
     * @test
     */
    public function override_tenant()
    {
        $this->environment->setTenant($this->createMockTenant());

        $override = factory(TenantIdentifiableByQueue::class)->create();

        Event::listen('mock.tenant.job', function ($event) use ($override) {
            $this->assertEquals($override->getTenantIdentifier(), $event->getTenantIdentifier());
            $this->assertEquals($override->getTenantKey(), $event->getTenantKey());
        });

        dispatch(new Mocks\Job(
            $override->getTenantKey(),
            $override->getTenantIdentifier()
        ));
    }
}
