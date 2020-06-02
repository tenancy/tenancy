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

namespace Tenancy\Identification\Drivers\Console\Listeners;

use Illuminate\Console\Events\ArtisanStarting;
use Symfony\Component\Console\Input\InputOption;

class GlobalTenantAwareness
{
    public function handle(ArtisanStarting $event)
    {
        $definition = $event->artisan->getDefinition();

        $definition->addOption(new InputOption('--tenant', null, InputOption::VALUE_REQUIRED, 'If specified, activate the given tenant.'));
        $definition->addOption(new InputOption('--tenant-identifier', null, InputOption::VALUE_REQUIRED, 'Filter available tenants based on the identifier (class or table for instance).'));

        $event->artisan->setDefinition($definition);
    }
}
