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

namespace Tenancy\Tests\Hooks\Migration\Unit;

use Tenancy\Hooks\Migration\Hooks\MigratesHook;
use Tenancy\Hooks\Migration\Hooks\SeedsHook;
use Tenancy\Hooks\Migration\Provider;
use Tenancy\Tests\Hooks\HookUnitTestCase;

class MigrationHookTest extends HookUnitTestCase
{
    /** @var array */
    protected $hooks = [
        MigratesHook::class,
        SeedsHook::class,
    ];

    /** @var string */
    protected $provider = Provider::class;
}
