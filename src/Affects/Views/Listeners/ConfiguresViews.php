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

use Illuminate\Contracts\View\Factory;
use Tenancy\Affects\Views\Events\ConfigureViews;
use Tenancy\Concerns\DispatchesEvents;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;

class ConfiguresViews implements TenantAffectsApp
{
    use DispatchesEvents;

    public function handle(Switched $event): ?bool
    {
        /** @var Factory $view */
        $view = resolve(Factory::class);

        if ($event->tenant) {
            $this->events()->dispatch(new ConfigureViews($event, $view));
        }

        return null;
    }
}
