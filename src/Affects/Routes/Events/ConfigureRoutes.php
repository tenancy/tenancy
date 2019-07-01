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

namespace Tenancy\Affects\Routes\Events;

use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Tenancy\Identification\Events\Switched;

class ConfigureRoutes
{
    /**
     * @var Switched
     */
    public $event;
    /**
     * @var Router
     */
    public $router;

    public function __construct(Switched $event, Router $router)
    {
        $this->event = $event;
        $this->router = $router;
    }

    /**
     * Flush all tenant routes for this request.
     *
     * @return $this
     */
    public function flush()
    {
        $this->router->setRoutes(new RouteCollection());

        return $this;
    }

    /**
     * Adds routes from a routes.php file to the current request.
     *
     * @param array  $attributes
     * @param string $path
     * @return $this
     */
    public function fromFile(array $attributes, string $path)
    {
        $this->router->group($attributes, $path);

        return $this;
    }
}
