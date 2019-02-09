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

namespace Tenancy\Identification\Drivers\Console\Listeners;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Events\Resolving;

class IdentifyByConsole
{
    public function handle(Resolving $event): ?Tenant
    {
        if (! app()->runningInConsole() || !app()->bound(InputInterface::class)) {
            return null;
        }

        $models = $event->models->filterByContract(IdentifiesByConsole::class);

        if ($models->isEmpty()) {
            return null;
        }

        return $models
            ->map(function ($tenant) {
                return app()->call("$tenant@tenantIdentificationByConsole");
            })
            ->filter()
            ->first();
    }
}
