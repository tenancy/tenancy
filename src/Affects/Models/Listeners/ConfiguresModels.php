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

namespace Tenancy\Affects\Models\Listeners;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionResolverInterface;
use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;

class ConfiguresModels implements TenantAffectsApp
{
    public function handle(Switched $event): ?bool
    {
        /** @var ConnectionResolverInterface $db */
        $db = resolve('db');
        /** @var Dispatcher $events */
        $events = resolve(Dispatcher::class);

        $events->dispatch(new ConfigureModels($event, $db));

        return null;
    }
}
