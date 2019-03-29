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

namespace Tenancy\Affects\Views\Listeners;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Tenancy\Affects\Views\Events\ConfigureViews;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Events\Switched;

class ConfiguresViews implements TenantAffectsApp
{
    /**
     * @param Resolved|Switched $event
     * @return bool|void
     */
    public function handle($event)
    {
        /** @var Factory $view */
        $view = resolve(Factory::class);
        /** @var Dispatcher $events */
        $events = resolve(Dispatcher::class);

        if ($event->tenant) {
            $events->dispatch(new ConfigureViews($event, $view));
        }
    }
}
