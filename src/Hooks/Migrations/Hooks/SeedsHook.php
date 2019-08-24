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

namespace Tenancy\Hooks\Migrations\Hooks;

use Tenancy\Facades\Tenancy;
use Illuminate\Database\Seeder;
use Tenancy\Tenant\Events\Deleted;
use Illuminate\Database\Eloquent\Model;
use Tenancy\Lifecycle\ConfigurableHook;
use Tenancy\Hooks\Migrations\Events\ConfigureSeeds;

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

        $this->resolve->__invoke(null, $this->connection);
        $db->setDefaultConnection($default);

        Model::reguard();
    }
}
