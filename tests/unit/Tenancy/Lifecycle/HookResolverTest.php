<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

 namespace Tenancy\Tests\Lifecycle;

use InvalidArgumentException;
use Tenancy\Lifecycle\Events\Resolved;
use Tenancy\Tenant\Events\Created;
use Tenancy\Testing\TestCase;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Tests\Lifecycle\Mocks\ConfiguredHook;
use Tenancy\Tests\Lifecycle\Mocks\InvalidHook;

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
            $low = $event->hooks->first();
            $high = $event->hooks->last();

            $this->assertEquals($hookLow, $low);
            $this->assertEquals($hookHigh, $high);
        });

        $resolver->handle(new Created($this->mockTenant()));
    }

    /**
     * @test
     */
    public function sets_hooks(){
        /** @var ResolvesHooks $resolver */
        $resolver = resolve(ResolvesHooks::class);

        $resolver->setHooks([]);
        $this->assertEquals(
            [],
            $resolver->getHooks()
        );

        $resolver->setHooks([
            ConfiguredHook::class
        ]);

        $this->assertEquals(
            [ConfiguredHook::class],
            $resolver->getHooks()
        );
    }
}
