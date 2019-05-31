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
