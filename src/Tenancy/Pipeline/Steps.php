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

namespace Tenancy\Pipeline;

use Illuminate\Support\Collection;
use Tenancy\Pipeline\Contracts\Step;

class Steps extends Collection
{
    public function resolve($event, Pipeline $pipeline)
    {
        return $this->map(function ($step) use ($event, $pipeline) {
            /** @var Step $hook */
            $step = is_string($step) ? resolve($step) : $step;

            $step = $step->for($event);

            event((new Events\Resolving($event, $pipeline))->step($step));

            return $step;
        })
        ->filter();
    }

    public function prioritized()
    {
        return $this->sortBy(function (Step $step) {
            return $step->priority();
        });
    }

    public function fires()
    {
        return $this->filter(function (Step $step) {
            return $step->fires();
        });
    }
}
