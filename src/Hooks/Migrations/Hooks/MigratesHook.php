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

namespace Tenancy\Hooks\Migrations\Hooks;

use Illuminate\Database\Migrations\Migrator;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migrations\Events\ConfigureMigrations;
use Tenancy\Lifecycle\ConfigurableHook;
use Tenancy\Tenant\Events\Deleted;

class MigratesHook extends ConfigurableHook
{
    /**
     * @var Migrator
     */
    public $migrator;

    public $connection;

    public $action;

    public $priority = -50;

    public function __construct()
    {
        $this->migrator = resolve('migrator');
        $this->connection = Tenancy::getTenantConnectionName();
    }

    public function for($event)
    {
        $this->action = $event instanceof Deleted ? 'reset' : 'run';

        parent::for($event);

        event(new ConfigureMigrations($event, $this));

        return $this;
    }

    public function fire(): void
    {
        $db = resolve('db');
        $default = $db->getDefaultConnection();

        $this->migrator->setConnection($this->connection);

        if (!$this->migrator->repositoryExists()) {
            $this->migrator->getRepository()->createRepository();
        }
        call_user_func([$this->migrator, $this->action], $this->migrator->paths());

        $db->setDefaultConnection($default);
    }
}
