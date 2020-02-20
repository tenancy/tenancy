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

namespace Tenancy\Affects\Views;

use Illuminate\Contracts\View\Factory;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresViews extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        if ($this->event->tenant) {
            /** @var Factory $view */
            $view = resolve(Factory::class);

            $this->events()->dispatch(new Events\ConfigureViews($this->event, $view));
        }
    }
}
