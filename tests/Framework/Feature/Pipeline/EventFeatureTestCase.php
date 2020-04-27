<?php

namespace Tenancy\Tests\Framework\Feature\Pipeline;

use Tenancy\Pipeline\Events\Event;
use Tenancy\Pipeline\Pipeline;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Pipeline\SimplePipeline;

abstract class EventFeatureTestCase extends TestCase
{
    protected $event = Event::class;

    /** @test */
    public function it_can_check_the_pipeline_it_is_for()
    {
        $event = $this->event;

        $event = new $event("TestEvent", new Pipeline());

        $this->assertTrue(
            $event->isForPipeline(Pipeline::class)
        );

        $this->assertFalse(
            $event->isForPipeline(SimplePipeline::class)
        );
    }
}
