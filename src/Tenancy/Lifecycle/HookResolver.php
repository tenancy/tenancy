<?php

namespace Tenancy\Lifecycle;

use InvalidArgumentException;
use Tenancy\Contracts\LifecycleHook;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Tenant\Events\Event;

class HookResolver implements ResolvesHooks
{
    protected $hooks = [];

    public function addHook(string $hook)
    {
        if (! in_array(LifecycleHook::class, class_implements($hook))) {
            throw new InvalidArgumentException("$hook has to implement " . LifecycleHook::class);
        }

        $this->hooks[] = $hook;

        return $this;
    }

    public function handle(Event $event)
    {
        collect($this->hooks)
            ->map(function (string $hook) use ($event) {
                /** @var LifecycleHook $hook */
                $hook = resolve($hook);

                return $hook->for($event);
            })
            ->sortByDesc(function (LifecycleHook $hook) {
                return $hook->priority();
            })
            ->filter(function (LifecycleHook $hook) {
                return $hook->fires();
            })
            ->each(function (LifecycleHook $hook) {
                if ($hook->queued()) {
                    dispatch(function () use ($hook) {
                        $hook->fire();
                    })->onQueue($hook->queue());
                } else {
                    $hook->fire();
                }
            });
    }
}