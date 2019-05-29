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

abstract class ConfigurableHook extends Hook
{
    /**
     * Queue connection to dispatch Hook on, or null for default.
     *
     * @var null|string|bool
     */
    public $queue = false;

    public $priority = 0;

    public function queued(): bool
    {
        return $this->queue !== false;
    }

    public function queue(): ?string
    {
        return $this->queue;
    }

    public function priority(): int
    {
        return $this->priority;
    }
}
