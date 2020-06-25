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

use Tenancy\Misc\Wingman\CLI\Interactions\Previous;

class PreviousTest extends BaseTestCase
{
    /** @var string */
    protected $class = Previous::class;

    /** @var string */
    protected $name = 'Previous';

    /** @var string */
    protected $shortcut = 'P';
}
