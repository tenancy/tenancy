<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Hooks\Migrations\Hooks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migrations\Events\ConfigureSeeds;
use Tenancy\Lifecycle\ConfigurableHook;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Event;

class SeedsHook extends ConfigurableHook
{
    public $connection;

    public $action;

    public $priority = -40;

    public $seeds = [];

    public function __construct()
    {
        $this->connection = Tenancy::getTenantConnectionName();
    }

    public function for(Event $event)
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

        $db->setDefaultConnection($this->connection);

        foreach ($this->seeds as $seed) {
            /** @var Seeder $seed */
            $seed = resolve($seed);
            $seed = $seed->setContainer(app());

            $seed();
        }

        $db->setDefaultConnection($default);

        Model::reguard();
    }
}
