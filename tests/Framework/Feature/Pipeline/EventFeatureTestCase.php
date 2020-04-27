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

        $event = new $event('TestEvent', new Pipeline());

        $this->assertTrue(
            $event->isForPipeline(Pipeline::class)
        );

        $this->assertFalse(
            $event->isForPipeline(SimplePipeline::class)
        );
    }
}
