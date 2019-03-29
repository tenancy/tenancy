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

namespace Tenancy\Affects\Routes\Events;

use Illuminate\Routing\Router;
use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Events\Switched;

class ConfigureRoutes
{
    /**
     * @var Resolved|Switched
     */
    public $event;
    /**
     * @var Router
     */
    private $router;

    public function __construct($event, Router $router)
    {
        $this->event = $event;
        $this->router = $router;
    }
}
