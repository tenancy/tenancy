<?php

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
