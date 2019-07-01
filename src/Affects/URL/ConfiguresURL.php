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

namespace Tenancy\Affects\URL\Listeners;

use Tenancy\Concerns\DispatchesEvents;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;
use Tenancy\Affects\URL\Events\ConfigureURL;
use Illuminate\Contracts\Routing\UrlGenerator;

class ConfiguresURL implements TenantAffectsApp
{
    use DispatchesEvents;

    public function handle(Switched $event): ?bool
    {
        /** @var UrlGenerator $url */
        $url = resolve(UrlGenerator::class);

        if ($event->tenant) {
            $this->events()->dispatch(new ConfigureURL($event, $url));
        }

        return null;
    }
}
