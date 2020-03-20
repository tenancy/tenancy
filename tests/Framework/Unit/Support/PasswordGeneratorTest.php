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

namespace Tenancy\Tests\Framework\Unit\Support;

use Tenancy\Support\Contracts\ProvidesPassword;
use Tenancy\Support\PasswordGenerator;
use Tenancy\Testing\TestCase;

class PasswordGeneratorTest extends TestCase
{
    /** @var ProvidesPassword */
    private $generator;

    protected function afterSetUp()
    {
        $this->generator = $this->app->make(ProvidesPassword::class);
    }

    /** @test */
    public function it_is_registered_by_default()
    {
        $this->assertInstanceOf(
            PasswordGenerator::class,
            $this->generator
        );
    }
}
