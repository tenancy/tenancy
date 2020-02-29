<?php

namespace Tenancy\Tests\Hooks;

use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Testing\TestCase;

abstract class HookUnitTestCase extends TestCase
{
    /** @var array */
    protected $hooks = [];

    /** @var string */
    protected $provider;

    /** @test */
    public function the_hooks_are_not_registered_by_default()
    {
        foreach($this->hooks as $hook){
            $this->assertNotContains(
                $hook,
                $this->app->make(ResolvesHooks::class)->getHooks()
            );
        }
    }

    /** @test */
    public function the_hooks_are_registered()
    {
        $this->app->register($this->provider);

        foreach($this->hooks as $hook){
            $this->assertContains(
                $hook,
                $this->app->make(ResolvesHooks::class)->getHooks()
            );
        }
    }
}
