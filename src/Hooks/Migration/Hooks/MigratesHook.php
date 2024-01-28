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

use Tenancy\Affects\Connections\Contracts\ResolvesConnections;
use Tenancy\Facades\Tenancy;
use Tenancy\Hooks\Migration\Events\ConfigureMigrations;
use Tenancy\Lifecycle\ConfigurableHook;
use Tenancy\Tenant\Events\Deleted;

class MigratesHook extends ConfigurableHook
{
    public string $connection;

    public $action;

    public int $priority = -50;

    protected bool $replaceDefaultConnection = true;

    public array $paths;

    public function __construct()
    {
        $this->connection = Tenancy::getTenantConnectionName();

        $this->paths = resolve('migrator')->paths();
    }

    public function for($event): static
    {
        $this->action = $event instanceof Deleted ? 'reset' : 'run';

        parent::for($event);

        event(new ConfigureMigrations($event, $this));

        return $this;
    }

    public function withDefaultConnection(bool $replace = true): static
    {
        $this->replaceDefaultConnection = $replace;

        return $this;
    }

    public function fire(): void
    {
        $db = resolve('db');
        $migrator = resolve('migrator');
        $resolver = resolve(ResolvesConnections::class);

        $default = $db->getDefaultConnection();

        $resolver->__invoke($this->event->tenant, $this->connection);
        $migrator->setConnection($this->connection);

        if (!$migrator->repositoryExists()) {
            $migrator->getRepository()->createRepository();
        }
        call_user_func([$migrator, $this->action], $this->paths);

        $resolver->__invoke(Tenancy::getTenant(), $this->connection);

        if ($this->replaceDefaultConnection) {
            $db->setDefaultConnection($default);
        }
    }
}
