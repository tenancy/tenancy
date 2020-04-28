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

namespace Tenancy\Hooks\Hostname;

use Tenancy\Hooks\Hostname\Contracts\ResolvesHostnames;
use Tenancy\Hooks\Hostname\Hooks\HostnamesHook;
use Tenancy\Providers\Provides\ProvidesBindings;
use Tenancy\Support\HooksProvider;

class Provider extends HooksProvider
{
    use ProvidesBindings;

    protected $hooks = [
        HostnamesHook::class,
    ];

    public $singletons = [
        ResolvesHostnames::class => HostnameResolver::class,
    ];
}
