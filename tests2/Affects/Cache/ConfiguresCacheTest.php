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

namespace Tenancy\Tests\Affects\Cache;

use Illuminate\Cache\CacheManager;
use Tenancy\Affects\Cache\Events\ConfigureCache;
use Tenancy\Affects\Cache\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresCacheTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var CacheManager
     */
    protected $cache;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
        $this->cache = $this->app->make(CacheManager::class);

        $this->events->listen(ConfigureCache::class, function (ConfigureCache $event) {
            $event->config['driver'] = 'file';
            $event->config['path'] = '/tmp/'.$event->event->tenant->getTenantKey();
        });
    }

    /**
     * @test
     */
    public function configuration_initially_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cache store [tenant] is not defined.');

        $this->cache->driver('tenant');
    }

    /**
     * @test
     */
    public function cache_valid_when_switched()
    {
        $this->resolveTenant($this->tenant);

        /** @var Tenant $tenant */
        $tenant = Tenancy::identifyTenant();

        $cache = $this->cache->driver('tenant');
        $cache->set('key', $tenant->getTenantKey());

        Tenancy::setTenant(null);

        $switched = Tenancy::getTenant();

        $this->assertNull($switched);

        Tenancy::setTenant($this->tenant);

        $cache = $this->cache->driver('tenant');

        $this->assertEquals($this->tenant->getTenantKey(), $cache->get('key'));
    }
}
