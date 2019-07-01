<?php

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
