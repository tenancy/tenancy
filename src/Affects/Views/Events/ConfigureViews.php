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

namespace Tenancy\Affects\Views\Events;

use Illuminate\Contracts\View\Factory;
use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Events\Switched;

class ConfigureViews
{
    /**
     * @var Resolved|Switched
     */
    public $event;
    /**
     * @var Factory|\Illuminate\View\Factory
     */
    public $view;

    public function __construct($event, Factory $view)
    {
        $this->event = $event;
        $this->view = $view;
    }

    /**
     * Configure a `tenant::` blade namespace.
     *
     * @param string $path
     * @param string $namespace
     * @return $this
     */
    public function addNamespace(string $path, string $namespace = 'tenant')
    {
        $this->view->addNamespace($namespace, $path);

        return $this;
    }

    /**
     * Enable lookups for blade files from an additional path.
     *
     * @param string $path
     * @param bool   $replace ; replace the existing global view directories.
     * @return $this
     */
    public function addPath(string $path, bool $replace = false)
    {
        if ($replace) {
            config(['view.paths' => $path]);
            $finder = $this->view->getFinder();

            $finder->prependLocation($path);
            $finder->flush();
        } else {
            $this->view->addLocation($path);
        }

        return $this;
    }
}
