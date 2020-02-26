<?php

namespace Tenancy\Tests\Framework\Unit\Hooks;

use InvalidArgumentException;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Lifecycle\HookResolver;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Hooks\InvalidHook;

class HookResolverTest extends TestCase
{
    /** @var ResolvesHooks */
    private $resolver;

    protected function afterSetUp()
    {
        $this->resolver = $this->app->make(ResolvesHooks::class);
    }

    /** @test */
    public function by_default_the_hook_resolver_is_registered()
    {
        $this->assertInstanceOf(
            HookResolver::class,
            $this->resolver
        );
    }

    /** @test */
    public function validates_hooks()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->resolver->addHook(InvalidHook::class);
    }
}
