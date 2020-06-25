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

use Tenancy\Misc\Wingman\CLI\Interactions\Page;

class PageTest extends BaseTestCase
{
    /** @var string */
    protected $class = Page::class;

    /** @var string */
    protected $name = 'Page';

    /** @var string */
    protected $shortcut = '#';

    /** @test */
    public function it_reacts_to_the_right_things()
    {
        $invalidInteractions = ['One', 'Two', 'P', 'Page', '#'];

        foreach ($invalidInteractions as $interaction) {
            $this->assertFalse(
                $this->interaction->shouldReact($interaction)
            );
        }

        $validInteractions = ['1','21'];

        foreach ($validInteractions as $interaction) {
            $this->assertTrue(
                $this->interaction->shouldReact($interaction)
            );
        }
    }
}
