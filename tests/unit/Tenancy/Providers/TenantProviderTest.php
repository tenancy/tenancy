<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Unit\Providers;

use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\TestCase;

class TenantProviderTest extends TestCase
{
    /**
     * @test
     */
    public function error_on_wrong_object()
    {
        $tenant = new \stdClass();

        $this->expectException(\TypeError::class);

        Tenancy::setTenant($tenant);
    }

    /**
     * @test
     */
    public function prefers_identified_tenant()
    {
        $tenant = $this->mockTenant();
        $newTenant = $this->mockTenant();

        $this->resolveTenant($newTenant);
        Tenancy::setTenant($tenant);

        $this->assertEquals(
            $tenant,
            resolve(Tenant::class)
        );
    }

    /**
     * @test
     */
    public function resolves_null()
    {
        $this->assertNull(resolve(Tenant::class));
    }

    /**
     * @test
     */
    public function resolves_tenant()
    {
        $tenant = $this->mockTenant();

        $this->resolveTenant($tenant);

        $this->assertEquals(
            $tenant,
            resolve(Tenant::class)
        );
    }
}
