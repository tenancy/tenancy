<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Identification\Queue;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Event;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Queue\Events\Processing;
use Tenancy\Identification\Drivers\Queue\Providers\IdentificationProvider;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Identification\Queue\Mocks\TenantIdentifiableInQueue;

class IdentifyInQueueTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];
    protected $additionalMocks = [__DIR__.'/Mocks/factories/'];

    /** @var ResolvesTenants */
    protected $resolver;

    protected function afterSetUp()
    {
        /** @var ResolvesTenants $resolver */
        $this->resolver = $this->app->make(ResolvesTenants::class);
        $this->resolver->addModel(Tenant::class);
    }

    /**
     * @test
     */
    public function queue_identifies_tenant()
    {
        $tenant = $this->mockTenant();

        $this->environment->setTenant($tenant);

        Event::listen(JobProcessed::class, function ($event) use ($tenant) {
            $payload = json_decode($event->job->getRawBody(), true);

            $this->assertEquals($tenant->getTenantIdentifier(), $payload['tenant_identifier']);
            $this->assertEquals($tenant->getTenantKey(), $payload['tenant_key']);
        });

        dispatch(new Mocks\Job());
    }

    /**
     * @test
     */
    public function override_tenant()
    {
        $tenant = $this->createMockTenant();
        $this->environment->setTenant($tenant);

        $second = $this->createMockTenant();

        Event::listen(Processing::class, function (Processing $event) {
            $event->switch();
        });

        Event::listen('mock.tenant.job', function ($event) use ($second) {
            $this->assertEquals($second->getTenantIdentifier(), $event->getTenantIdentifier());
            $this->assertEquals($second->getTenantKey(), $event->getTenantKey());
        });

        dispatch(new Mocks\Job(
            $second->getTenantKey(),
            $second->getTenantIdentifier()
        ));
    }

    /**
     * @test
     */
    public function override_in_processing_event()
    {
        /** @var Tenant $tenant */
        $tenant = $this->createMockTenant();

        /** @var Tenant $second */
        $second = $this->createMockTenant();

        Event::listen(Processing::class, function (Processing $event) use ($second) {
            $event->switch($second);
        });

        Event::listen('mock.tenant.job', function ($event) use ($second) {
            $this->assertEquals($second->getTenantIdentifier(), $event->getTenantIdentifier());
            $this->assertEquals($second->getTenantKey(), $event->getTenantKey());
        });

        dispatch(new Mocks\Job(
            $tenant->getTenantKey(),
            $tenant->getTenantIdentifier()
        ));
    }

    /**
     * @test
     */
    public function override_using_contract()
    {
        /** @var Tenant $tenant */
        $tenant = $this->createMockTenant();
        $this->resolver->addModel(Mocks\TenantIdentifiableInQueue::class);

        TenantIdentifiableInQueue::query()->forceDelete();
        /** @var TenantIdentifiableInQueue $second */
        $second = factory(Mocks\TenantIdentifiableInQueue::class)->create();

        Event::listen(Processing::class, function (Processing $event) {
            $event->switch($event->resolve());
        });

        Event::listen('mock.tenant.job', function ($event) use ($second) {
            $this->assertEquals($second->getTenantIdentifier(), $event->getTenantIdentifier());
            $this->assertEquals($second->getTenantKey(), $event->getTenantKey());
        });

        dispatch(new Mocks\Job(
            $tenant->getTenantKey(),
            $tenant->getTenantIdentifier()
        ));
    }
}
