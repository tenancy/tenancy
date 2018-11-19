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

namespace Tenancy\Identification\Drivers\Console\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Tenancy\Identification\Drivers\Console\Listeners\IdentifyByConsole;
use Tenancy\Identification\Drivers\Console\Middleware\EagerIdentification;
use Tenancy\Identification\Events\Resolving;

class IdentificationProvider extends EventServiceProvider
{
    protected $listen = [
        CommandStarting::class => [
            EagerIdentification::class
        ],
        Resolving::class => [
            IdentifyByConsole::class
        ]
    ];
}
