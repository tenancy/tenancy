<?php

namespace Tenancy\Affects\Migrations\Hooks;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Migrations\Migrator;
use Tenancy\Affects\Migrations\Events\ConfigureTenantMigrations;
use Tenancy\Contracts\TenantLifecycle;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;

class MigratesTenant implements TenantLifecycle
{
    protected function migrations($event): Migrator
    {
        /** @var Dispatcher $events */
        $events = resolve(Dispatcher::class);

        $paths = $options = [];

        $events->dispatch(new ConfigureTenantMigrations($event, $paths, $options));

        /** @var Migrator $migrator */
        $migrator = resolve(Migrator::class);

        $migrator->resolveConnection();

        if ($event instanceof Deleted) {
            return $migrator->reset($paths, $options['pretend'] ?? false);
        }

        return $migrator->run($paths, $options);
    }

    public function created(Created $event): void
    {
        return $this->migrations($event);
    }

    public function updated(Updated $event): void
    {
        return $this->migrations($event);
    }

    public function deleted(Deleted $event): void
    {
        return $this->migrations($event);
    }
}