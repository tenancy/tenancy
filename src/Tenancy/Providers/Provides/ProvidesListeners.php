<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Providers\Provides;

use Illuminate\Support\Facades\Event;
use Tenancy\Affects\Contracts\ResolvesAffects;
use Tenancy\Identification\Events\Switched;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Tenant\Events as Tenant;

trait ProvidesListeners
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Tenant\Created::class => [
            ResolvesHooks::class,
        ],
        Tenant\Updated::class => [
            ResolvesHooks::class,
        ],
        Tenant\Deleted::class => [
            ResolvesHooks::class,
        ],
        Switched::class => [
            ResolvesAffects::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
    ];

    public function bootProvidesListeners()
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            Event::subscribe($subscriber);
        }
    }
}
