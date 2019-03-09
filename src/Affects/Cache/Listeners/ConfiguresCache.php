<?php

namespace Tenancy\Affects\Cache\Listeners;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Tenancy\Affects\Cache\Events\ConfigureCache;
use Tenancy\Identification\Events\Resolved;

class ConfiguresCache
{
    public function handle(Resolved $event)
    {
        /** @var Factory|CacheManager $cache */
        $manager = resolve(Factory::class);
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
    }
}
