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

namespace Tenancy\Tests\Misc\Wingman\Feature\CLI\Commands;

use Illuminate\Support\Facades\Event;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Misc\Wingman\Provider;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

abstract class EventCommandTestCase extends TestCase
{
    protected $additionalProviders = [Provider::class];

    protected $command = '';

    protected $event = '';

    protected $resolver;

    protected function afterSetUp()
    {
        $this->resolver = $this->app->make(ResolvesTenants::class);
        $this->resolver->addModel(Tenant::class);
    }

    /** @test */
    public function it_fires_the_right_event_for_tenants()
    {
        factory(Tenant::class)->create();

        Event::fake();

        $this->artisan($this->command);

        Event::assertDispatched($this->event);
    }

    /** @test */
    public function it_can_distinguish_tenants()
    {
        $tenantOne = factory(Tenant::class)->create();
        $tenantTwo = factory(Tenant::class)->create();

        Event::fake();

        $this->artisan($this->command, [
            '--tenants' => [$tenantOne->getTenantKey()]
        ]);

        Event::assertDispatched($this->event, function($event) use ($tenantOne){
            return $event->tenant->getTenantKey() === $tenantOne->getTenantKey();
        });

        Event::assertNotDispatched($this->event, function ($event) use ($tenantTwo){
            return $event->tenant->getTenantKey() === $tenantTwo->getTenantKey();
        });
    }

    /** @test */
    public function it_can_distinguish_tenants_from_strings()
    {
        $tenantOne = factory(Tenant::class)->create();
        $tenantTwo = factory(Tenant::class)->create();
        $tenantThree = factory(Tenant::class)->create();

        Event::fake();

        $this->artisan($this->command, [
            '--tenants' => $tenantOne->getTenantKey() . ',' . $tenantTwo->getTenantKey()
        ]);

        Event::assertDispatched($this->event, function($event) use ($tenantThree){
            return $event->tenant->getTenantKey() != $tenantThree->getTenantKey();
        });
    }

    /** @test */
    public function it_can_distinguish_tenant_identifiers()
    {
        $tenantOne = factory(Tenant::class)->create();

        Event::fake();

        $this->artisan($this->command, [
            '--tenant-identifiers' => ['ThisDoesNotExist']
        ]);

        Event::assertNotDispatched($this->event, function($event) use ($tenantOne){
            return $event->tenant->getTenantIdentifier() === $tenantOne->getTenantIdentifier();
        });
    }
}
