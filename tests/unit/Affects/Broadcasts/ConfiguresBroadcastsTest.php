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

use Illuminate\Broadcasting\BroadcastManager;
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

        $this->app->resolving(BroadcastManager::class, function (BroadcastManager $manager){
            $manager->extend('fake', function(){
                return new Mocks\FakeBroadcaster();
            });
        });

        $this->events->listen(ConfigureBroadcast::class, function (ConfigureBroadcast $event) {
            $event->config['driver'] = 'fake';
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

        $broadcaster = $this->getBroadcastManager()->driver('tenant');
        $options = ['tenant-key' => $tenant->getTenantKey()];
        $broadcaster->channel('tenancy', true, $options);

        $this->assertEquals(
            $options,
            $broadcaster->retrieveChannelOptions('tenancy')
        );

        Tenancy::setTenant(null);

        $switched = Tenancy::getTenant();

        $this->assertNull($switched);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Driver [] is not supported.');
        $broadcaster = $this->getBroadcastManager()->driver('tenant');

        Tenancy::setTenant($this->tenant);

        $broadcaster = $this->getBroadcastManager()->driver('tenant');
        $this->assertEquals(
            $options,
            $broadcaster->retrieveChannelOptions('tenancy')
        );
    }

    protected function getBroadcastManager()
    {
        return resolve(BroadcastManager::class);
    }
}
