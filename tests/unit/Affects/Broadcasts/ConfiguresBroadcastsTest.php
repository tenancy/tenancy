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

namespace Tenancy\Tests\Affects\Broadcasts;

use Illuminate\Broadcasting\Broadcasters\LogBroadcaster;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Tenancy\Affects\Broadcasts\Events\ConfigureBroadcast;
use Tenancy\Affects\Broadcasts\Provider;
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

        $this->events->listen(ConfigureBroadcast::class, function (ConfigureBroadcast $event) {
            $event->config['driver'] = 'log';
        });
    }

    /**
     * @test
     */
    public function configuration_initially_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [] is not supported.');

        $this->broadcast->driver('tenant');
    }
    /**
     * @test
     */
    public function broadcaster_valid_when_switched()
    {
        $this->resolveTenant($this->tenant);

        /** @var Tenant $tenant */
        $tenant = Tenancy::getTenant();

        $broadcaster = $this->broadcast->driver('tenant');

        $this->assertInstanceOf(
            Broadcaster::class,
            $broadcaster
        );

        $this->assertInstanceOf(
            LogBroadcaster::class,
            $broadcaster
        );
    }
}
