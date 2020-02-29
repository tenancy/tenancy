<?php

namespace Tenancy\Tests\Hooks\Database\Unit;

use Tenancy\Hooks\Database\Hooks\DatabaseMutation;
use Tenancy\Hooks\Database\Provider;
use Tenancy\Tests\Hooks\HookUnitTestCase;

class DatabaseHookTest extends HookUnitTestCase
{
    /** @var array */
    protected $hooks = [
        DatabaseMutation::class
    ];

    /** @var string */
    protected $provider = Provider::class;
}
