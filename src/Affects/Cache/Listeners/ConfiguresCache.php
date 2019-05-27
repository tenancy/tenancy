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

namespace Tenancy\Affects\Cache\Listeners;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Tenancy\Affects\Cache\Events\ConfigureCache;
use Tenancy\Contracts\TenantAffectsApp;
use Tenancy\Identification\Events\Switched;

class ConfiguresCache implements TenantAffectsApp
{
    /**
     * @param Switched $event
     * @return bool|void
     */
    public function handle(Switched $event): ?bool
    {
        /** @var CacheManager $manager¸ */
        $manager = resolve(CacheManager::class);
        /** @var Repository $config */
        $config = resolve(Repository::class);
        /** @var Dispatcher $events */
        $events = resolve(Dispatcher::class);

        if ($event->tenant) {
            $cacheConfig = [];

            $events->dispatch(new ConfigureCache($event, $cacheConfig));
        }

        $config->set('cache.stores.tenant', $cacheConfig ?? null);

        // Force reload of tenant cache driver upon next request.
        $manager->forgetDriver('tenant');

        return null;
    }
}
