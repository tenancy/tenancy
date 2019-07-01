<?php

namespace Tenancy\Pipeline;

use Tenancy\Pipeline\Contracts\Step;
use Tenancy\Pipeline\Events\Fired;

class Pipeline
{
    protected $steps;

    public function __construct(Steps $steps = null)
    {
        $this->steps = $steps ?? new Steps();
    }

    public function getSteps(): Steps
    {
        return $this->steps;
    }

    /**
     * @param array|Step[] $steps
     * @return Pipeline
     */
    public function setSteps(array $steps): Pipeline
    {
        $this->steps = new Steps($steps);

        return $this;
    }

    public function handle($event, callable $fire = null)
    {
        $steps = $this->steps
            ->map(function ($step) use ($event) {
                /** @var Step $hook */
                $step = is_string($step) ? resolve($step) : $step;

                $step = $step->for($event);

                event((new Events\Resolving($event, $this))->step($step));

                return $step;
            })
            ->sortBy(function (Step $step) {
                return $step->priority();
            })
            ->filter(function (Step $step) {
                return $step->fires();
            });

        event((new Events\Resolved($event, $this))->steps($steps));

        if ($fire) {
            $fire($steps);
        } else {
            $steps->each(function (Step $step) {
                $step->fire();
            });
        }

        event(new Events\Fired($event, $this));

        return $steps;
    }


    public function __call($name, $arguments)
    {
        return $this->steps->{$name}($arguments);
    }
}