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

namespace Tenancy\Tests\Framework\Feature\Hooks;

use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Queue;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Pipeline\Events\Resolved;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Hooks\ConfiguredHook;

class HookResolverTest extends TestCase
{
    /** @var ResolvesHooks */
    private $resolver;

    protected function afterSetUp()
    {
        $this->resolver = $this->app->make(ResolvesHooks::class);
    }

    /** @test */
    public function it_prioritizes_hooks_on_handle()
    {
        $this->resolver->setHooks([]);

        $hookLow = new ConfiguredHook();
        $hookLow->priority = -100;
        $this->resolver->addHook($hookLow);

        $hookHigh = new ConfiguredHook();
        $hookHigh->priority = 100;
        $this->resolver->addHook($hookHigh);

        $this->events->listen(Resolved::class, function (Resolved $event) use ($hookLow, $hookHigh) {
            $this->assertEquals($hookLow, $event->steps->first());
            $this->assertEquals($hookHigh, $event->steps->last());
        });

        $this->resolver->handle(new Created($this->mockTenant()));
    }

    /** @test */
    public function sets_hooks()
    {
        $this->resolver->setHooks([]);

        $this->assertEquals(
            [],
            $this->resolver->getHooks()
        );

        $this->resolver->setHooks([
            ConfiguredHook::class,
        ]);

        $this->assertEquals(
            [ConfiguredHook::class],
            $this->resolver->getHooks()
        );
    }

    /** @test */
    public function can_queue()
    {
        Queue::fake();

        $this->resolver->setHooks([]);

        $hook = new ConfiguredHook();
        $hook->queue = 'test';
        $this->resolver->addHook($hook);

        $this->resolver->handle(new Created($this->mockTenant()));

        Queue::assertPushed(CallQueuedClosure::class);
    }
}
