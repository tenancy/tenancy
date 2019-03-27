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
use Illuminate\Contracts\Events\Dispatcher;
use Tenancy\Affects\Logs\Events\ConfigureLogs;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Events\Switched;

class ConfiguresLogs implements TenantAffectsApp
{
    /**
     * @param Resolved|Switched $event
     * @return bool|void
     */
    public function handle($event)
    {
        /** @var Repository $config */
        $config = resolve(Repository::class);
        /** @var Dispatcher $events */
        $events = resolve(Dispatcher::class);

        if ($event->tenant) {
            $logConfig = [];

            $events->dispatch(new ConfigureLogs($event, $logConfig));
        }

        // Configure the tenant log channel.
        $config->set('logging.channels.tenant', $logConfig ?? null);

        // There is currently no way to unset a log channel :(
        app()->forgetInstance('log');
    }
}
