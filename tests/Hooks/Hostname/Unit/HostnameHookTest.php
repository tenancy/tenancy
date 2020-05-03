<?php

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
