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

namespace Tenancy\Lifecycle;

use InvalidArgumentException;
use Tenancy\Contracts\LifecycleHook;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Lifecycle\Events\Fired;
use Tenancy\Lifecycle\Events\Resolved;
use Tenancy\Lifecycle\Events\Resolving;
use Tenancy\Tenant\Events\Event;

class HookResolver implements ResolvesHooks
{
    protected $hooks = [];

    public function addHook($hook)
    {
        if (! in_array(LifecycleHook::class, class_implements($hook))) {
            throw new InvalidArgumentException("$hook has to implement " . LifecycleHook::class);
        }

        $this->hooks[] = $hook;

        return $this;
    }

    public function handle(Event $event)
    {
        $hooks = collect($this->hooks)
            ->map(function ($hook) use ($event) {
                /** @var LifecycleHook $hook */
                $hook = is_string($hook) ? resolve($hook) : $hook;

                $hook = $hook->for($event);

                event(new Resolving($event, $hook));

                return $hook;
            })
            ->sortBy(function (LifecycleHook $hook) {
                return $hook->priority();
            })
            ->filter(function (LifecycleHook $hook) {
                return $hook->fires();
            });

        event(new Resolved($event, $hooks));

        $hooks->each(function (LifecycleHook $hook) {
            if ($hook->queued()) {
                dispatch(function () use ($hook) {
                    $hook->fire();
                })->onQueue($hook->queue());
            } else {
                $hook->fire();
            }
        });

        event(new Fired($event, $hooks));
    }
}
