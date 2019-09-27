<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Affects\Broadcasting;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresBroadcasting extends Affect
{
    use DispatchesEvents;

    /**
     * Executes the logic.
     */
    public function fire(): void
    {
        /** @var BroadcastManager $manager¸ */
        $manager = resolve(BroadcastManager::class);

        /** @var Repository $config */
        $config = resolve(Repository::class);

        if ($this->event->tenant) {
            $broadcastingConfig = [];

            $this->events()->dispatch(new Events\ConfiguresBroadcasting($this->event, $broadcastingConfig));
        }

        $config->set('broadcasting.connections.tenant', $broadcastingConfig ?? null);

        // Force reload of tenant cache driver upon next request.
        $manager->forgetDriver('tenant');
    }
}
