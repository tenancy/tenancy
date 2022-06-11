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
    public function __construct(
        public Switched $event,
        public Factory $view
    ) {
    }

    public function addNamespace(string $path, string $namespace = 'tenant'): static
    {
        $this->view->addNamespace($namespace, $path);

        return $this;
    }

    public function addPath(string $path, bool $replace = false): static
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
