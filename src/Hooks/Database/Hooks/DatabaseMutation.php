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

namespace Tenancy\Hooks\Database\Hooks;

use Illuminate\Support\Arr;
use Tenancy\Lifecycle\Hook;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;
use Tenancy\Hooks\Database\Contracts\ResolvesDatabases;

class DatabaseMutation extends Hook
{
    const PRIORITY = -100;

    protected $mapping = [
        Created::class => 'create',
        Updated::class => 'update',
        Deleted::class => 'delete',
    ];

    public function priority(): int
    {
        return static::PRIORITY;
    }

    public function fires(): bool
    {
        return Arr::has($this->mapping, get_class($this->event));
    }

    public function fire(): void
    {
        /** @var ResolvesDatabases $resolver */
        $resolver = resolve(ResolvesDatabases::class);

        $driver = $resolver($this->event->tenant);

        $action = $this->mapping[get_class($this->event)];

        if ($driver && config("tenancy.database.auto-$action")) {
            $driver->{$action}($this->event->tenant);
        }
    }
}
