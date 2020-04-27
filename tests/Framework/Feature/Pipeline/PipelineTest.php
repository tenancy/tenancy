<?php

namespace Tenancy\Tests\Framework\Feature\Pipeline;

use Illuminate\Support\Facades\Event;
use Tenancy\Pipeline\Contracts\Step;
use Tenancy\Pipeline\Pipeline;
use Tenancy\Pipeline\Steps;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Pipeline\SimpleStep;
use Tenancy\Pipeline\Events;

class PipelineTest extends TestCase
{
    /** @test */
    public function it_can_be_constructed_with_steps()
    {
        $steps = new Steps([
            new SimpleStep
        ]);

        $pipeline = new Pipeline($steps);

        $this->assertEquals(
            $steps,
            $pipeline->getSteps()
        );
    }

    /** @test */
    public function it_can_set_steps()
    {
        $pipeline = new Pipeline();

        $pipeline->setSteps([new SimpleStep]);

        $this->assertNotEmpty($pipeline->getSteps());

        $pipeline->getSteps()->each(function (Step $step){
            $this->assertInstanceOf(SimpleStep::class, $step);
        });
    }

    /** @test */
    public function it_forwards_calls_to_steps()
    {
        $prioritizedStep = new SimpleStep;
        $prioritizedStep->priority = -100;

        $steps = new Steps([
            new SimpleStep,
            $prioritizedStep
        ]);

        $pipeline = new Pipeline($steps);

        $last = -1000;
        $pipeline->prioritized()->each(function (Step $step) use ($last) {
            $this->assertTrue($step->priority > $last);
            $last = $step->priority;
        });
    }

    /** @test */
    public function handle_fires_the_events_with_data()
    {
        Event::fake([
            Events\Processing::class,
            Events\Resolved::class,
            Events\Fired::class
        ]);

        $pipeline = new Pipeline();

        $pipeline->handle("TestEvent");

        Event::assertDispatched(Events\Processing::class, function ($event){
            return $event->event === "TestEvent";
        });
        Event::assertDispatched(Events\Resolved::class, function ($event){
            return $event->event === "TestEvent";
        });
        Event::assertDispatched(Events\Fired::class, function ($event){
            return $event->event === "TestEvent";
        });
    }
}
