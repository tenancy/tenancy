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

namespace Tenancy\Hooks\Hostname\Events;

use Tenancy\Hooks\Hostname\Hooks\HostnamesHook;
use Tenancy\Tenant\Events\Event;

class ConfigureHostnames
{
    public function __construct(
        public Event $event,
        public HostnamesHook $hook
    ) {}

    public function disable(): static
    {
        $this->hook->fires = false;

        return $this;
    }

    public function priority(int $priority = -50): static
    {
        $this->hook->priority = $priority;

        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->hook, $name], $arguments);
    }
}
