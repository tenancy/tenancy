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

use Tenancy\Pipeline\Pipeline;

abstract class Event
{
    public $event;

    /**
     * @var Pipeline
     */
    public $pipeline;

    public function __construct($event, Pipeline $pipeline)
    {
        $this->event = $event;
        $this->pipeline = $pipeline;
    }

    public function isForPipeline($pipeline): bool
    {
        $pipeline = is_string($pipeline) ? $pipeline : get_class($pipeline);

        return get_class($this->pipeline) === $pipeline;
    }
}
