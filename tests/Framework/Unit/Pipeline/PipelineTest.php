<?php

namespace Tenancy\Tests\Framework\Unit\Pipeline;

use Tenancy\Pipeline\Pipeline;
use Tenancy\Testing\TestCase;

class PipelineTest extends TestCase
{
    /** @test */
    public function it_gets_constructed_with_no_steps()
    {
        $this->assertEmpty((new Pipeline())->getSteps());
    }
}
