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

namespace Tenancy\Affects\Views\Events;

use Illuminate\Contracts\View\Factory;
use Tenancy\Identification\Events\Switched;

class ConfigureViews
{
    /**
     * @var Switched
     */
    public $event;

    /**
     * @var Factory|\Illuminate\View\Factory
     */
    public $view;

    public function __construct(Switched $event, Factory $view)
    {
        $this->event = $event;
        $this->view = $view;
    }

    /**
     * Configure a `tenant::` blade namespace.
     *
     * @param string $path
     * @param string $namespace
     *
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
     *
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
