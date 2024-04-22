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

namespace Tenancy\Hooks\Migration\Hooks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Tenancy\Affects\Connections\Contracts\ResolvesConnections;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migration\Events\ConfigureSeeds;
use Tenancy\Lifecycle\ConfigurableHook;
use Tenancy\Tenant\Events\Deleted;

class SeedsHook extends ConfigurableHook
{
    public string $connection;

    public ?string $action = null;

    public int $priority = -40;

    protected bool $replaceDefaultConnection = true;

    /** @var array|string[] */
    public array $seeds = [];

    public function __construct()
    {
        $this->connection = Tenancy::getTenantConnectionName();
    }

    public function for($event): static
    {
        $this->action = $event instanceof Deleted ? 'reset' : 'run';

        parent::for($event);

        event(new ConfigureSeeds($event, $this));

        return $this;
    }

    public function withDefaultConnection(bool $replace = true): static
    {
        $this->replaceDefaultConnection = $replace;

        return $this;
    }

    public function fire(): void
    {
        if (empty($this->seeds)) {
            return;
        }

        $db = resolve('db');
        $resolver = resolve(ResolvesConnections::class);

        $default = $db->getDefaultConnection();

        Model::unguard();

        $resolver->__invoke($this->event->tenant, $this->connection);

        if ($this->replaceDefaultConnection) {
            $db->setDefaultConnection($this->connection);
        }

        foreach ($this->seeds as $seed) {
            /** @var Seeder $seeder */
            $seeder = resolve($seed);
            $seeder = $seeder->setContainer(app());

            $seeder();
        }

        $resolver->__invoke(Tenancy::getTenant(), $this->connection);

        if ($this->replaceDefaultConnection) {
            $db->setDefaultConnection($default);
        }

        Model::reguard();
    }
}
