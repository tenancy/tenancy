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
    public $connection;

    public $action;

    public $priority = -40;

    public $seeds = [];

    public function __construct()
    {
        $this->connection = Tenancy::getTenantConnectionName();
        $this->resolver = resolve(ResolvesConnections::class);
    }

    public function for($event)
    {
        $this->action = $event instanceof Deleted ? 'reset' : 'run';

        parent::for($event);

        event(new ConfigureSeeds($event, $this));

        return $this;
    }

    public function fire(): void
    {
        if (empty($this->seeds)) {
            return;
        }

        $db = resolve('db');

        $default = $db->getDefaultConnection();

        Model::unguard();

        $this->resolver->__invoke($this->event->tenant, $this->connection);
        $db->setDefaultConnection($this->connection);

        foreach ($this->seeds as $seed) {
            /** @var Seeder $seed */
            $seed = resolve($seed);
            $seed = $seed->setContainer(app());

            $seed();
        }

        $this->resolver->__invoke(null, $this->connection);
        $db->setDefaultConnection($default);

        Model::reguard();
    }
}
