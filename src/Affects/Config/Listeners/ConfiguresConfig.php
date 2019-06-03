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

namespace Tenancy\Affects\Config\Listeners;

use Tenancy\Concerns\DispatchesEvents;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;
use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Config\Events\ConfigureConfig;

class ConfiguresConfig implements TenantAffectsApp
{
    use DispatchesEvents;

    public function handle(Switched $event): ?bool
    {
        /** @var Repository $config */
        $config = resolve(Repository::class);

        if ($event->tenant) {
            $this->events()->dispatch(new ConfigureConfig($event, $config));
        }

        return null;
    }
}
