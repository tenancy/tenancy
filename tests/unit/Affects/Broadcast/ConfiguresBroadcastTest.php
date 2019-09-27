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

namespace Tenancy\Tests\Affects\Broadcast;

use Illuminate\Broadcasting\BroadcastManager;
use Tenancy\Affects\Broadcasting\Events\ConfiguresBroadcasting;
use Tenancy\Affects\Broadcasting\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresBroadcastTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var BroadcastManager
     */
    protected $broadcast;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
        $this->broadcast = $this->app->make(BroadcastManager::class);

        $this->events->listen(ConfiguresBroadcasting::class, function (ConfiguresBroadcasting $event) {
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
        $this->expectExceptionMessage('Broadcasting channel [tenant] is not defined.');

        $this->broadcast->driver('tenant');
    }

    /**
     * @test
     */
    public function cache_valid_when_switched()
    {
        $this->resolveTenant($this->tenant);

        /** @var Tenant $tenant */
        $tenant = Tenancy::getTenant();

        $cache = $this->broadcast->driver('tenant');
        $cache->set('key', $tenant->getTenantKey());

        Tenancy::setTenant(null);

        $switched = Tenancy::getTenant();

        $this->assertNull($switched);

        Tenancy::setTenant($this->tenant);

        $cache = $this->broadcast->driver('tenant');

        $this->assertEquals($this->tenant->getTenantKey(), $cache->get('key'));
    }
}
