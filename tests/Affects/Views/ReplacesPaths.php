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

namespace Tenancy\Tests\Affects\Views;

use Tenancy\Affects\Views\Events\ConfigureViews;
use Tenancy\Tests\UsesViews;

trait ReplacesPaths
{
    use UsesViews;

    /**
     * Registers the affecting in the application.
     *
     * @return void
     */
    protected function registerAffecting()
    {
        $this->events->listen(ConfigureViews::class, function (ConfigureViews $event) {
            $event->addPath($this->getViewsPath(), true);
        });
    }
}
