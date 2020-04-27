<?php

namespace Tenancy\Tests\Framework\Feature\Pipeline\Events;

use Tenancy\Pipeline\Contracts\Step;
use Tenancy\Pipeline\Events\Processing;
use Tenancy\Pipeline\Pipeline;
use Tenancy\Pipeline\Steps;
use Tenancy\Tests\Framework\Feature\Pipeline\EventFeatureTestCase;
use Tenancy\Tests\Mocks\Pipeline\SimpleStep;

class ProcessingTest extends EventFeatureTestCase
{
    protected $event = Processing::class;

    /** @test */
    public function it_forwards_calls_to_the_steps()
    {
        $event = $this->event;

        $prioritizedStep = new SimpleStep;
        $prioritizedStep->priority = -100;

        $steps = new Steps([
            new SimpleStep,
            $prioritizedStep
        ]);

        $event = new $event("TestEvent", new Pipeline());
        $event->steps = $steps;

        $last = -1000;
        $event->prioritized()->each(function (Step $step) use ($last) {
            $this->assertTrue($step->priority > $last);
            $last = $step->priority;
        });
    }

}
