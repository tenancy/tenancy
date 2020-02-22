<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\Routes;

use Tenancy\Affects\Routes\Events\ConfigureRoutes;
use Tenancy\Tests\UsesRoutes;

trait AddsFromFile
{
    use UsesRoutes;

    /**
     * Registers the affecting in the application.
     *
     * @return void
     */
    protected function registerAffecting()
    {
        $this->events->listen(ConfigureRoutes::class, function (ConfigureRoutes $event) {
            $event->fromFile($this->getRouteAttributes(), $this->getTestRoutesPath());
        });
    }

    /**
     * Gets the route attributes for the file that is loaded.
     *
     * @return array
     */
    protected function getRouteAttributes()
    {
        return [];
    }

    /**
     * Gets the path to the routes used for this test.
     *
     * @return string
     */
    protected function getTestRoutesPath()
    {
        return $this->getTenantRoutesPath();
    }
}
