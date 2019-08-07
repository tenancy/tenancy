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

namespace Tenancy\Affects\Routes;

use Illuminate\Routing\Router;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresRoutes extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var Router $router */
        $router = resolve(Router::class);

        if ($this->event->tenant) {
            $this->events()->dispatch(new Events\ConfigureRoutes($this->event, $router));
        }

        $router->getRoutes()->refreshNameLookups();
        $router->getRoutes()->refreshActionLookups();
    }
}
