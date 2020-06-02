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

namespace Tenancy\Tests\Framework\Feature\Providers;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

class TenantProviderTest extends TestCase
{
    /** @test */
    public function it_can_resolve_null()
    {
        $this->assertNull(resolve(Tenant::class));
    }

    /** @test */
    public function it_can_resolve_the_tenant_from_the_environment()
    {
        $tenant = $this->mockTenant();
        $this->environment->setTenant($tenant);

        $this->assertEquals(
            $tenant,
            resolve(Tenant::class)
        );
    }
}
