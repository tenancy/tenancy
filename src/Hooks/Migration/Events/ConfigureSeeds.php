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
    /**
     * @var Event
     */
    public $event;

    /**
     * @var SeedsHook
     */
    public $hook;

    public function __construct(Event $event, SeedsHook $hook)
    {
        $this->event = $event;
        $this->hook = $hook;
    }

    public function seed(string $class)
    {
        $this->hook->seeds[] = $class;

        return $this;
    }

    public function disable()
    {
        $this->hook->fires = false;

        return $this;
    }

    public function priority(int $priority = -40)
    {
        $this->hook->priority = $priority;

        return $this;
    }
}
