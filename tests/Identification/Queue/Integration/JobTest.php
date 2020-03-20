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

namespace Tenancy\Tests\Identification\Queue\Integration;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Tenancy\Identification\Drivers\Queue\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Jobs\SimpleJob;

class JobTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    /** @test */
    public function jobs_do_not_contain_the_tenant_when_none_identified()
    {
        Event::listen([JobProcessing::class, JobProcessed::class], function ($event) {
            $payload = json_decode($event->job->getRawBody(), true);

            $this->assertArrayNotHasKey('tenant_identifier', $payload);
            $this->assertArrayNotHasKey('tenant_key', $payload);
        });

        dispatch(new SimpleJob());
    }

    /** @test */
    public function jobs_do_contain_the_tenant_when_one_is_identified()
    {
        $tenant = $this->mockTenant();

        $this->environment->setTenant($tenant);

        Event::listen([JobProcessing::class, JobProcessed::class], function ($event) use ($tenant) {
            $payload = json_decode($event->job->getRawBody(), true);

            $this->assertEquals($tenant->getTenantIdentifier(), $payload['tenant_identifier']);
            $this->assertEquals($tenant->getTenantKey(), $payload['tenant_key']);
        });

        dispatch(new SimpleJob());
    }
}
