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

use Tenancy\Pipeline\Contracts\Step as Contract;

abstract class Step implements Contract
{
    public $event;

    public $priority = 0;

    public $fires = true;

    public function for($event)
    {
        $this->event = $event;

        return $this;
    }

    public function priority(): int
    {
        return $this->priority;
    }

    public function fires(): bool
    {
        return $this->fires;
    }
}
