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

namespace Tenancy\Tests\Hooks\Database\Unit;

use Tenancy\Hooks\Database\Contracts\ResolvesDatabases;
use Tenancy\Hooks\Database\Events;
use Tenancy\Hooks\Database\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Testing\Concerns\InteractsWithDatabases;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Database\NullDriver;

class DatabaseResolverDispatchingTest extends TestCase
{
    use InteractsWithDatabases;

    protected array $additionalProviders = [Provider::class];

    /** @var Tenant */
    protected $tenant;

    /** @var ResolvesDatabases */
    private $resolver;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
        $this->resolver = $this->app->make(ResolvesDatabases::class);
    }

    /** @test */
    public function without_driver()
    {
        $resolving = $resolved = $identified = $configuring = 0;

        $this->expectEvent(Events\Resolving::class, $resolving);
        $this->expectEvent(Events\Identified::class, $identified);
        $this->expectEvent(Events\Resolved::class, $resolved);

        $this->resolver->__invoke($this->tenant);

        $this->assertEquals(1, $resolving);
        $this->assertEquals(0, $identified);
        $this->assertEquals(1, $resolved);
    }

    /** @test */
    public function with_driver()
    {
        $resolving = $resolved = $identified = $configuring = 0;

        $this->resolveDatabase(function () {
            return new NullDriver();
        });

        $this->expectEvent(Events\Resolving::class, $resolving);
        $this->expectEvent(Events\Identified::class, $identified);
        $this->expectEvent(Events\Resolved::class, $resolved);

        $this->resolver->__invoke($this->tenant);

        $this->assertEquals(0, $resolving);
        $this->assertEquals(1, $identified);
        $this->assertEquals(1, $resolved);
    }

    protected function expectEvent(string $event, int &$count)
    {
        $this->events->listen($event, function () use (&$count) {
            $count++;
        });
    }
}
