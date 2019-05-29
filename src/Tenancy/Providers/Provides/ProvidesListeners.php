<?php declare(strict_types=1);

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

namespace Tenancy\Providers\Provides;

use Illuminate\Support\Facades\Event;
use Tenancy\Database\Contracts\ResolvesConnections;
use Tenancy\Database\Events as Database;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Tenant\Events as Tenant;
use Tenancy\Database\Listeners as Listen;

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
        Database\Resolved::class => [
            Listen\SetConnection::class
        ]
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

        $this->subscribe[] = resolve(ResolvesConnections::class);

        foreach ($this->subscribe as $subscriber) {
            Event::subscribe($subscriber);
        }
    }
}
