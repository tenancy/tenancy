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

namespace Tenancy\Tests\Hooks\Database;

use Tenancy\Hooks\Database\Events;
use Tenancy\Hooks\Database\Provider;
use Tenancy\Tenant\Events as Tenant;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Testing\TestCase;

class DatabaseResolverDispatchingTest extends TestCase
{
    use InteractsWithDatabases;

    protected $additionalProviders = [Provider::class];

    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function without_driver()
    {
        $resolving = $resolved = $identified = $configuring = 0;

        $this->expectEvent(Events\Resolving::class, $resolving);
        $this->expectEvent(Events\Identified::class, $identified);
        $this->expectEvent(Events\Resolved::class, $resolved);
        $this->expectEvent(Events\Drivers\Configuring::class, $configuring);

        $this->events->dispatch(new Tenant\Created($this->tenant));

        $this->assertEquals(1, $resolving);
        $this->assertEquals(0, $identified);
        $this->assertEquals(1, $resolved);
        $this->assertEquals(0, $configuring);
    }

    /**
     * @test
     */
    public function with_driver()
    {
        $resolving = $resolved = $identified = $configuring = 0;

        $this->resolveDatabase(function () {
            return new Mocks\NullDriver();
        });

        $this->expectEvent(Events\Resolving::class, $resolving);
        $this->expectEvent(Events\Identified::class, $identified);
        $this->expectEvent(Events\Resolved::class, $resolved);
        $this->expectEvent(Events\Drivers\Configuring::class, $configuring);

        $this->events->dispatch(new Tenant\Created($this->tenant));

        $this->assertEquals(0, $resolving);
        $this->assertEquals(1, $identified);
        $this->assertEquals(1, $resolved);
        $this->assertEquals(1, $configuring);
    }

    protected function expectEvent(string $event, int &$count)
    {
        $this->events->listen($event, function () use (&$count) {
            $count++;
        });
    }
}
