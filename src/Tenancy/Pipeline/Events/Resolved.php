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
