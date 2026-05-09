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

namespace Tenancy\Tests\Hooks;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tenancy\Tenant\Events as Tenant;
use Tenancy\Testing\TestCase;

abstract class ConfigureHookTestCase extends TestCase
{
    /** @var string */
    protected $hookClass;

    /** @var string */
    protected $eventClass;

    protected $hook;

    protected function afterSetUp()
    {
        $this->hook = $this->app->make($this->hookClass);
    }

    #[Test]
    #[DataProvider('tenantEventsProvider')]
    public function it_can_disable_the_hook($tenantEvent)
    {
        $this->events->listen($this->eventClass, function ($event) {
            $event->disable();
        });

        $this->hook->for(new $tenantEvent($this->getMockedTenant()));

        $this->assertFalse($this->hook->fires);
    }

    #[DataProvider('tenantEventsProvider')]
    #[Test]
    public function it_can_prioritize_the_hook($tenantEvent)
    {
        $original = $this->hook->priority();

        $this->events->listen($this->eventClass, function ($event) {
            $event->priority(-10000);
        });

        $this->hook->for(new $tenantEvent($this->getMockedTenant()));

        $this->assertNotEquals(
            $original,
            $this->hook->priority()
        );
    }

    public static function tenantEventsProvider(): array
    {
        return [
            [Tenant\Created::class],
            [Tenant\Updated::class],
            [Tenant\Deleted::class],
        ];
    }

    public function getMockedTenant()
    {
        return $this->mockTenant();
    }
}
