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

namespace Tenancy\Affects\Routes\Listeners;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use Tenancy\Concerns\DispatchesEvents;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;

class ConfiguresRoutes implements TenantAffectsApp
{
    use DispatchesEvents;

    public function handle(Switched $event): ?bool
    {
        /** @var Router $router */
        $router = resolve(Router::class);

        if ($event->tenant) {
            $this->events()->dispatch(new ConfigureRoutes($event, $router));
        }

        $router->getRoutes()->refreshNameLookups();
        $router->getRoutes()->refreshActionLookups();

        return null;
    }
}
