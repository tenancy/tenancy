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

namespace Tenancy\Identification\Drivers\Console\Providers;

use Illuminate\Console\Events\ArtisanStarting;
use Illuminate\Console\Events\CommandStarting;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Console\Listeners\GlobalTenantAwareness;
use Tenancy\Identification\Drivers\Console\Middleware\EagerIdentification;
use Tenancy\Providers\Provides\ProvidesListeners;
use Tenancy\Support\DriverProvider;

class IdentificationProvider extends DriverProvider
{
    use ProvidesListeners;

    protected $drivers = [
        IdentifiesByConsole::class,
    ];

    protected $listen = [
        ArtisanStarting::class => [
            GlobalTenantAwareness::class,
        ],
        CommandStarting::class => [
            EagerIdentification::class,
        ],
    ];
}
