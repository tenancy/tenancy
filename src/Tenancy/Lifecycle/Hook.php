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

namespace Tenancy\Lifecycle;

use Tenancy\Contracts\LifecycleHook;
use Tenancy\Pipeline\Step;
use Tenancy\Tenant\Events\Event;

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

    public function fires(): bool
    {
        return $this->event instanceof Event && parent::fires();
    }
}
