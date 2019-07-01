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
