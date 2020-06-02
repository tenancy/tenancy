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

use Tenancy\Pipeline\Steps;

class Resolved extends Event
{
    public $steps;

    public function steps(Steps &$steps)
    {
        $this->steps = &$steps;

        return $this;
    }

    public function __call($name, $arguments)
    {
        return $this->steps->{$name}($arguments);
    }
}
