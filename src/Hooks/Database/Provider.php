<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Hooks\Database;

use Tenancy\Support\HooksProvider;
use Tenancy\Providers\Provides\ProvidesBindings;
use Tenancy\Hooks\Database\Contracts\ResolvesDatabases;

class Provider extends HooksProvider
{
    use ProvidesBindings;

    protected $hooks = [
        Hooks\DatabaseMutation::class,
    ];

    public $singletons = [
        ResolvesDatabases::class => DatabaseResolver::class
    ];
}
