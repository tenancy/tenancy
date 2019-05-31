<?php

namespace Tenancy\Lifecycle\Events;

use Tenancy\Contracts\LifecycleHook;
use Tenancy\Tenant\Events\Event;

class Resolving
{
    /**
     * @var Event
     */
    public $event;
    /**
     * @var LifecycleHook
     */
    public $hook;

    public function __construct(Event $event, LifecycleHook &$hook)
    {
        $this->event = $event;
        $this->hook = &$hook;
    }
}