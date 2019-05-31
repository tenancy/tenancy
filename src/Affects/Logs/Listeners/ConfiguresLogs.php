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

namespace Tenancy\Affects\Logs\Listeners;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Logs\Events\ConfigureLogs;
use Tenancy\Concerns\DispatchesEvents;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;

class ConfiguresLogs implements TenantAffectsApp
{
    use DispatchesEvents;

    public function handle(Switched $event): ?bool
    {
        /** @var Repository $config */
        $config = resolve(Repository::class);

        if ($event->tenant) {
            $logConfig = [];

            $this->events()->dispatch(new ConfigureLogs($event, $logConfig));
        }

        // Configure the tenant log channel.
        $config->set('logging.channels.tenant', $logConfig ?? null);

        // There is currently no way to unset a log channel :(
        app()->forgetInstance('log');

        return null;
    }
}
