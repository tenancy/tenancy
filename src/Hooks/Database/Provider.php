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

namespace Tenancy\Hooks\Database;

use Tenancy\Hooks\Database\Contracts\ResolvesDatabases;
use Tenancy\Support\HooksProvider;

class Provider extends HooksProvider
{
    protected $hooks = [
        Hooks\DatabaseMutation::class,
    ];

    public $singletons = [
        ResolvesDatabases::class => DatabaseResolver::class,
    ];
}
