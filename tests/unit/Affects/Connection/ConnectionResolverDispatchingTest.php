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
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\Connection;

use Tenancy\Affects\Connection\Events;
use Tenancy\Affects\Connection\Provider;
use Tenancy\Affects\Connection\Support\InteractsWithConnections;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;

class ConnectionResolverDispatchingTest extends TestCase
{
    use InteractsWithConnections;

    protected $additionalProviders = [Provider::class];

    /**
     * @test
     */
    public function not_callback_identified()
    {
        $resolving = $resolved = $identified = $configuring = 0;

        $this->expectEvent(Events\Resolving::class, $resolving);
        $this->expectEvent(Events\Identified::class, $identified);
        $this->expectEvent(Events\Resolved::class, $resolved);
        $this->expectEvent(Events\Drivers\Configuring::class, $configuring);

        Tenancy::setTenant();

        $this->assertEquals(1, $resolving);
        $this->assertEquals(0, $identified);
        $this->assertEquals(1, $resolved);
        $this->assertEquals(0, $configuring);
    }

    /**
     * @test
     */
    public function simple_listener()
    {
        $this->resolveConnection(function () {
            return new Mocks\ConnectionListener();
        });

        $this->configureConnection(function ($event) {
            $event->useConnection('sqlite', $event->configuration);
        });

        $resolving = $resolved = $identified = $configuring = 0;

        $this->expectEvent(Events\Resolving::class, $resolving);
        $this->expectEvent(Events\Identified::class, $identified);
        $this->expectEvent(Events\Resolved::class, $resolved);
        $this->expectEvent(Events\Drivers\Configuring::class, $configuring);

        Tenancy::setTenant();

        $this->assertEquals(0, $resolving);
        $this->assertEquals(1, $identified);
        $this->assertEquals(1, $resolved);
        $this->assertEquals(0, $configuring);
    }

    protected function expectEvent(string $event, int &$count)
    {
        $this->events->listen($event, function () use (&$count) {
            $count++;
        });
    }
}
