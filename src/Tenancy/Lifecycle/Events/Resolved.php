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
