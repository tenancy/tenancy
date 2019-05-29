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

use Tenancy\Contracts\LifecycleHook;
use Tenancy\Tenant\Events\Event;

abstract class Hook implements LifecycleHook
{
    /**
     * @var Event
     */
    protected $event;

    public function for(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    public function fires(): bool
    {
        return true;
    }

    public function queued(): bool
    {
        return true;
    }

    public function priority(): int
    {
        return 0;
    }

    public function queue(): ?string
    {
        return null;
    }
}
