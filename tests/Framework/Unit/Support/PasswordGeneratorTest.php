<?php

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
