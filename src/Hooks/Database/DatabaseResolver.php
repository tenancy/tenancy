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

namespace Tenancy\Hooks\Database;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Traits\Macroable;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Hooks\Database\Contracts\ResolvesDatabases;
use Tenancy\Identification\Contracts\Tenant;

class DatabaseResolver implements ResolvesDatabases
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

    public function __invoke(Tenant $tenant = null): ?ProvidesDatabase
    {
        /** @var ProvidesDatabase|null $provider */
        $provider = $this->events->until(new Events\Resolving($tenant));

        if ($provider) {
            $this->events->dispatch(new Events\Identified($tenant, $provider));
        }

        $this->events->dispatch(new Events\Resolved($tenant, $provider));

        return $provider;
    }
}
