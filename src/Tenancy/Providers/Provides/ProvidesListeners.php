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
use Tenancy\Tenant\Events as TenantEvents;
use Tenancy\Database\Listeners as Database;

trait ProvidesListeners
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        TenantEvents\Created::class => [
            Database\AutoCreation::class,
        ],
        TenantEvents\Updated::class => [
            Database\AutoUpdating::class,
        ],
        TenantEvents\Deleted::class => [
            Database\AutoDeleting::class,
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
