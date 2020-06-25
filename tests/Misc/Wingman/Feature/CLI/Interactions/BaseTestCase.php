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

namespace Tenancy\Tests\Misc\Wingman\Feature\CLI\Interactions;

use Tenancy\Testing\TestCase;

abstract class BaseTestCase extends TestCase
{
    /** @var string */
    protected $class;

    /** @var string */
    protected $name;

    /** @var string */
    protected $shortcut;

    protected function afterSetUp()
    {
        $this->interaction = new $this->class();
    }

    /** @test */
    public function the_shortcut_is_right()
    {
        $this->assertEquals(
            $this->shortcut,
            $this->interaction->getShortcut()
        );
    }

    /** @test */
    public function the_name_is_right()
    {
        $this->assertEquals(
            $this->name,
            $this->interaction->getName()
        );
    }

    /** @test */
    public function options_is_not_an_empty_array()
    {
        $this->assertNotEmpty($this->interaction->getOptions());
    }

    /** @test */
    public function options_contains_the_name()
    {
        $this->assertContains($this->interaction->getName(), $this->interaction->getOptions());
    }

    /** @test */
    public function options_contains_the_shortcut()
    {
        $this->assertContains($this->interaction->getShortcut(), $this->interaction->getOptions());
    }

    /** @test */
    public function it_reacts_to_the_right_things()
    {
        foreach ($this->interaction->getOptions() as $option) {
            $this->assertTrue(
                $this->interaction->shouldReact($option)
            );
        }
    }
}
