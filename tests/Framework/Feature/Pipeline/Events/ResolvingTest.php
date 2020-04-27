<?php

namespace Tenancy\Tests\Framework\Feature\Pipeline\Events;

use Tenancy\Pipeline\Events\Resolving;
use Tenancy\Pipeline\Pipeline;
use Tenancy\Tests\Framework\Feature\Pipeline\EventFeatureTestCase;
use Tenancy\Tests\Mocks\Pipeline\SimpleStep;

class ResolvingTest extends EventFeatureTestCase
{
    protected $event = Resolving::class;

    /** @test */
    public function it_can_set_a_step()
    {
        $event = $this->event;

        $step = new SimpleStep;
        $step->priority = -100;

        $event = new $event("TestEvent", new Pipeline());
        $event->step($step);

        $this->assertEquals(
            $step,
            $event->step
        );
    }

    /** @test */
    public function it_can_replace_a_step()
    {
        $event = $this->event;

        $step = new SimpleStep;
        $step->priority = -100;

        $event = new $event("TestEvent", new Pipeline());
        $event->step($step);

        $this->assertEquals(
            -100,
            $event->step->priority
        );

        $event->replace(new SimpleStep());

        $this->assertEquals(
            0,
            $event->step->priority
        );
    }

    /** @test */
    public function it_can_remove_a_step()
    {
        $event = $this->event;

        $step = new SimpleStep;
        $step->priority = -100;

        $event = new $event("TestEvent", new Pipeline());
        $event->step($step);

        $event->remove();
        $this->assertNull($event->step);
    }
}
