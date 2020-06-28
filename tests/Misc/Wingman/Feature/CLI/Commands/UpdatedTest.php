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
use Tenancy\Tenant\Events\Updated;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class UpdatedTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /** @test */
    public function it_fires_updated_events_for_tenants()
    {
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel(Tenant::class);

        factory(Tenant::class)->create();

        Event::fake();

        $this->artisan('wingman:updated');

        Event::assertDispatched(Updated::class);
    }
}
