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

namespace Tenancy\Hooks\Hostname\Hooks;

use Tenancy\Hooks\Hostname\Contracts\HasHostnames;
use Tenancy\Hooks\Hostname\Contracts\HostnameHandler;
use Tenancy\Hooks\Hostname\Events\ConfigureHostnames;
use Tenancy\Lifecycle\ConfigurableHook;

class HostnamesHook extends ConfigurableHook
{
    private array $handlers = [];

    public bool $fires = false;

    public function for($event): static
    {
        parent::for($event);

        if (in_array(HasHostnames::class, class_implements($event->tenant))) {
            $this->fires = true;

            event(new ConfigureHostnames($event, $this));
        }

        return $this;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function registerHandler(HostnameHandler $handler): static
    {
        $this->handlers[] = $handler;

        return $this;
    }

    public function fire(): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($this->event);
        }
    }
}
