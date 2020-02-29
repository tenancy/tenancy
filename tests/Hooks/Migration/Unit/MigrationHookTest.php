<?php

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
        SeedsHook::class
    ];

    /** @var string */
    protected $provider = Provider::class;
}
