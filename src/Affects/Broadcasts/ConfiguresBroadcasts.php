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

namespace Tenancy\Affects\Broadcasts;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresBroadcasts extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var BroadcastManager $manager */
        $manager = resolve(BroadcastManager::class);

        /** @var Repository $config */
        $config = resolve(Repository::class);

        $broadcastConfig = [];

        $this->events()->dispatch(new Events\ConfigureBroadcast($this->event, $broadcastConfig));

        $config->set('broadcasting.connections.tenant', $broadcastConfig ?? null);

        // There is no other way at this moment :c
        app()->forgetInstance(BroadcastManager::class);
    }
}
