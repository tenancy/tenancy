<?php

namespace Tenancy\Lifecycle\Events;

use Illuminate\Support\Collection;
use Tenancy\Tenant\Events\Event;

class Resolved
{
    /**
     * @var Event
     */
    public $event;
    /**
     * @var Collection
     */
    public $hooks;

    public function __construct(Event $event, Collection &$hooks)
    {
        $this->event = $event;
        $this->hooks = &$hooks;
    }
}