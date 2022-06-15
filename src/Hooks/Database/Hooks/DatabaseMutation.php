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

namespace Tenancy\Hooks\Database\Hooks;

use Illuminate\Support\Arr;
use Tenancy\Hooks\Database\Contracts\ProvidesDatabase;
use Tenancy\Hooks\Database\Contracts\ResolvesDatabases;
use Tenancy\Hooks\Database\Events\ConfigureDatabaseMutation;
use Tenancy\Lifecycle\ConfigurableHook;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;

class DatabaseMutation extends ConfigurableHook
{
    public int $priority = -100;

    public bool $fires = false;

    protected ?ProvidesDatabase $driver = null;

    protected array $mapping = [
        Created::class => 'create',
        Updated::class => 'update',
        Deleted::class => 'delete',
    ];

    public function for($event): static
    {
        parent::for($event);

        if (Arr::has($this->mapping, get_class($this->event))) {
            $this->fires = true;

            event(new ConfigureDatabaseMutation($event, $this));

            /** @var ResolvesDatabases $resolver */
            $resolver = resolve(ResolvesDatabases::class);

            $this->driver = $resolver($this->event->tenant);
        }

        return $this;
    }

    public function fire(): void
    {
        $action = $this->mapping[get_class($this->event)];

        $this->driver?->{$action}($this->event->tenant);
    }
}
