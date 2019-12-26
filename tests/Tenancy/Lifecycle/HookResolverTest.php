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

namespace Tenancy\Tests\Framework\Lifecycle;

use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Queue;
use InvalidArgumentException;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Lifecycle\HookResolver;
use Tenancy\Pipeline\Events\Resolved;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Framework\Lifecycle\Mocks\ConfiguredHook;
use Tenancy\Tests\Framework\Lifecycle\Mocks\DefaultHook;
use Tenancy\Tests\Framework\Lifecycle\Mocks\InvalidHook;

class HookResolverTest extends TestCase
{
    /**
     * @test
     */
    public function validates_hooks()
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var ResolvesHooks $resolver */
        $resolver = resolve(ResolvesHooks::class);
        $resolver->addHook(InvalidHook::class);
    }

    /**
     * @test
     */
    public function prioritizes()
    {
        /** @var ResolvesHooks $resolver */
        $resolver = resolve(ResolvesHooks::class);

        $resolver->setHooks([]);

        $hookLow = new ConfiguredHook();
        $hookLow->priority = -100;
        $resolver->addHook($hookLow);

        $hookHigh = new ConfiguredHook();
        $hookHigh->priority = 100;
        $resolver->addHook($hookHigh);

        $this->events->listen(Resolved::class, function (Resolved $event) use ($hookLow, $hookHigh) {
            $this->assertEquals($hookLow, $event->steps->first());
            $this->assertEquals($hookHigh, $event->steps->last());
        });

        $resolver->handle(new Created($this->mockTenant()));
    }

    /**
     * @test
     */
    public function sets_hooks()
    {
        /** @var ResolvesHooks $resolver */
        $resolver = resolve(ResolvesHooks::class);

        $resolver->setHooks([]);
        $this->assertEquals(
            [],
            $resolver->getHooks()
        );

        $resolver->setHooks([
            ConfiguredHook::class,
        ]);

        $this->assertEquals(
            [ConfiguredHook::class],
            $resolver->getHooks()
        );
    }

    /**
     * @test
     */
    public function can_queue()
    {
        Queue::fake();

        /** @var ResolvesHooks $resolver */
        $resolver = resolve(ResolvesHooks::class);

        $resolver->setHooks([]);

        $hook = new ConfiguredHook();
        $hook->queue = 'test';
        $resolver->addHook($hook);

        $resolver->handle(new Created($this->mockTenant()));

        Queue::assertPushed(CallQueuedClosure::class, function ($job) {
            return true;
        });
    }

    /**
     * @test
     */
    public function default_hook_after_own()
    {
        /** @var ResolvesHooks $resolver */
        $resolver = resolve(ResolvesHooks::class);

        $resolver->addHook($hook = new DefaultHook());

        $this->events->listen(Resolved::class, function (Resolved $event) use ($hook) {
            if ($event->isForPipeline(HookResolver::class)) {
                $this->assertEquals($hook, $event->steps->last());
            }
        });

        $resolver->handle(new Created($this->mockTenant()));
    }
}
