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

namespace Tenancy\Tests\Hooks\Hostname\Unit;

use Tenancy\Hooks\Hostname\Hooks\HostnamesHook;
use Tenancy\Hooks\Hostname\Provider;
use Tenancy\Tests\Hooks\HookUnitTestCase;

class HostnameHookTest extends HookUnitTestCase
{
    /** @var array */
    protected $hooks = [
        HostnamesHook::class,
    ];

    /** @var string */
    protected $provider = Provider::class;
}
