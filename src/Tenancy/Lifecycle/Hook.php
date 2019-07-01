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

namespace Tenancy\Lifecycle;

use Tenancy\Contracts\LifecycleHook;
use Tenancy\Pipeline\Step;

abstract class Hook extends Step implements LifecycleHook
{
    public $queued = true;
    public $queue = null;

    public function queued(): bool
    {
        return $this->queued;
    }

    public function queue(): ?string
    {
        return $this->queue;
    }
}
