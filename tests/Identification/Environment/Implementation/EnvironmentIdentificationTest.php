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

namespace Tenancy\Tests\Identification\Environment\Implementation;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Environment\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Tenants\SimpleEnvironmentTenant;

class EnvironmentIdentificationTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    protected function afterSetUp()
    {
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel(SimpleEnvironmentTenant::class);
    }

    /** @test */
    public function it_can_identify_null()
    {
        $this->createMockTenant();

        $this->assertNull(
            $this->environment->identifyTenant()
        );
    }

    /** @test */
    public function it_can_identify_a_tenant_by_name()
    {
        $tenant = $this->createMockTenant();

        putenv("TENANT=$tenant->name");

        $this->assertEquals(
            $tenant->name,
            $this->environment->identifyTenant()->name
        );
    }
}
