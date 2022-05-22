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

namespace Tenancy\Affects\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresCache extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var CacheManager $manager */
        $manager = resolve(CacheManager::class);

        /** @var Repository $config */
        $config = resolve(Repository::class);

        $cacheConfig = [];

        $this->events()->dispatch(new Events\ConfigureCache($this->event, $cacheConfig));

        $config->set('cache.stores.tenant', $cacheConfig ?? null);

        // Force reload of tenant cache driver upon next request.
        $manager->forgetDriver('tenant');
    }
}
