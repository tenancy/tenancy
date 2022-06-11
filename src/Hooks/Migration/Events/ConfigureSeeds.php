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

namespace Tenancy\Hooks\Migration\Events;

use Tenancy\Hooks\Migration\Hooks\SeedsHook;
use Tenancy\Tenant\Events\Event;

class ConfigureSeeds
{
    public function __construct(
        public Event $event,
        public SeedsHook $hook
    ) {
    }

    public function seed(string $class): static
    {
        $this->hook->seeds[] = $class;

        return $this;
    }

    public function disable(): static
    {
        $this->hook->fires = false;

        return $this;
    }

    public function priority(int $priority = -40): static
    {
        $this->hook->priority = $priority;

        return $this;
    }
}
