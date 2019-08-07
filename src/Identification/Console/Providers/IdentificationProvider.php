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

namespace Tenancy\Identification\Drivers\Console\Providers;

use Illuminate\Console\Events\ArtisanStarting;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Tenancy\Identification\Drivers\Console\Listeners\GlobalTenantAwareness;
use Tenancy\Identification\Drivers\Console\Listeners\IdentifyByConsole;
use Tenancy\Identification\Drivers\Console\Middleware\EagerIdentification;
use Tenancy\Identification\Events\Resolving;

class IdentificationProvider extends EventServiceProvider
{
    protected $listen = [
        ArtisanStarting::class => [
            GlobalTenantAwareness::class,
        ],
        CommandStarting::class => [
            EagerIdentification::class,
        ],
        Resolving::class => [
            IdentifyByConsole::class,
        ],
    ];
}
