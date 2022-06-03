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

namespace Tenancy\Tests\Affects\Cache\Integration;

use Illuminate\Support\Facades\Cache;
use Tenancy\Affects\Cache\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsIntegrationTestCase;
use Tenancy\Tests\Affects\Cache\UsesFileDriver;

class ConfigureCacheFileTest extends AffectsIntegrationTestCase
{
    use UsesFileDriver;

    protected array $additionalProviders = [Provider::class];

    /** @test */
    public function it_can_store_data()
    {
        Tenancy::setTenant($this->tenant);

        Cache::driver('tenant')->set('tenant_test', $this->tenant->getTenantKey());

        $this->assertEquals(
            $this->tenant->getTenantKey(),
            Cache::driver('tenant')->get('tenant_test')
        );
    }

    /** @test */
    public function data_is_not_shared_across_tenants()
    {
        Tenancy::setTenant($this->tenant);

        Cache::driver('tenant')->set('tenant_test', $this->tenant->getTenantKey());

        Tenancy::setTenant($this->mockTenant());

        $this->assertNotEquals(
            $this->tenant->getTenantKey(),
            Cache::driver('tenant')->get('tenant_test')
        );
    }
}
