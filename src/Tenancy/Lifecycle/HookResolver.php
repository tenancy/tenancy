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

namespace Tenancy\Lifecycle;

use InvalidArgumentException;
use Tenancy\Contracts\LifecycleHook;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Pipeline\Pipeline;

class HookResolver extends Pipeline implements ResolvesHooks
{
    public function addHook($hook)
    {
        if (!in_array(LifecycleHook::class, class_implements($hook))) {
            throw new InvalidArgumentException("$hook has to implement ".LifecycleHook::class);
        }

        $this->steps->add($hook);

        return $this;
    }

    public function getHooks(): array
    {
        return $this->getSteps()->toArray();
    }

    public function setHooks(array $hooks)
    {
        $this->setSteps($hooks);

        return $this;
    }

    public function handle($event, callable $fire = null)
    {
        parent::handle($event, function ($hooks) {
            $hooks->each(function (LifecycleHook $hook) {
                if ($hook->queued()) {
                    dispatch(function () use ($hook) {
                        // @codeCoverageIgnoreStart
                        $hook->fire();
                        // @codeCoverageIgnoreEnd
                    })->onQueue($hook->queue());
                } else {
                    $hook->fire();
                }
            });
        });
    }
}
