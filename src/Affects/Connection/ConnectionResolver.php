<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Affects\Connection;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Traits\Macroable;
use Tenancy\Affects\Connection\Contracts\ResolvesConnections;
use Tenancy\Database\Events;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Identification\Contracts\Tenant;

class ConnectionResolver implements ResolvesConnections
{
    use Macroable;

    /**
     * @var Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(Switched::class, function ($event) {
            $this($event->tenant);
        });
    }

    public function __invoke(Tenant $tenant = null, string $connection = null): ?ProvidesDatabase
    {
        /** @var ProvidesDatabase|null $provider */
        $provider = $this->events->until(new Events\Resolving($tenant, $connection));

        if ($provider) {
            $this->events->dispatch(new Events\Identified($tenant, $connection, $provider));
        }

        $this->events->dispatch(new Events\Resolved($tenant, $connection, $provider));

        return $provider;
    }
}
