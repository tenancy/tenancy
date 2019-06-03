<?php

namespace Tenancy\Support;

use Illuminate\Support\ServiceProvider;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;

abstract class HooksProvider extends ServiceProvider
{
    /**
     * Lifecycle event hooks. Hooks that run specific logic
     * during Tenant creation, updates or deletion.
     *
     * @var array
     */
    protected $hooks = [];

    public function register()
    {
        $this->app->resolving(ResolvesHooks::class, function (ResolvesHooks $resolver) {
            foreach ($this->hooks as $hook) {
                $resolver->addHook($hook);
            }
        });
    }
}