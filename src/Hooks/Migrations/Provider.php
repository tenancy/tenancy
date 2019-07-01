<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Hooks\Migrations;

use Tenancy\Hooks\Migrations\Hooks\MigratesHook;
use Tenancy\Hooks\Migrations\Hooks\SeedsHook;
use Tenancy\Support\HooksProvider;

class Provider extends HooksProvider
{
    protected $hooks = [
        MigratesHook::class,
        SeedsHook::class
    ];
}
