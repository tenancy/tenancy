<?php

namespace Tenancy\Tests\Misc\Wingman\Feature\CLI\Interactions;

use Tenancy\Misc\Wingman\CLI\Interactions\Quit;

class QuitTest extends BaseTestCase
{
    /** @var string */
    protected $class = Quit::class;

    /** @var string */
    protected $name = 'Quit';

    /** @var string */
    protected $shortcut = 'Q';
}
