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
