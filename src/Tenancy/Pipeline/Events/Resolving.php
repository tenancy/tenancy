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

namespace Tenancy\Pipeline\Events;

use Tenancy\Pipeline\Step;

class Resolving extends Event
{
    public $step;

    public function step(Step &$step)
    {
        $this->step = &$step;

        return $this;
    }

    public function replace(Step $with)
    {
        $this->step = $with;

        return $this;
    }

    public function remove()
    {
        $this->step = null;

        return $this;
    }
}
