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

namespace Tenancy\Tests\Identification\Console\Implementation;

use Illuminate\Foundation\Console\Kernel;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Console\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Tenants\SimpleConsoleTenant;

class SimpleConsoleTenantTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    protected function afterSetUp()
    {
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel(SimpleConsoleTenant::class);
        $this->app->make(Kernel::class)->command(
            'identifies',
            function () {
            }
        );
    }

    /** @test */
    public function it_can_identify_null()
    {
        $this->createMockTenant();

        $this->artisan('identifies', [
            '--tenant' => 'Name does not exist',
        ]);

        $this->assertNull(
            $this->environment->getTenant()
        );
    }

    /** @test */
    public function it_can_identify_a_tenant_by_name()
    {
        $tenant = $this->createMockTenant();

        $this->artisan('identifies', [
            '--tenant' => $tenant->name,
        ]);

        $this->assertEquals(
            $tenant->name,
            $this->environment->getTenant()->name
        );
    }
}
