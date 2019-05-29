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

namespace Tenancy\Affects\Migrations\Hooks;

use Illuminate\Database\Migrations\Migrator;
use Tenancy\Affects\Migrations\Events\ConfigureTenantMigrations;
use Tenancy\Lifecycle\Hook;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;

class MigratesHook extends Hook
{
    protected function migrations($event): ?Migrator
    {
        $paths = $options = [];
        $options['method'] = $event instanceof Deleted ? 'reset' : 'run';

        event(new ConfigureTenantMigrations($event, $paths, $options));

        $method = $options['method'];

        if (! $method) {
            return null;
        }

        /** @var Migrator $migrator */
        $migrator = resolve(Migrator::class);

        $migrator->resolveConnection();

        return $migrator->{$method}($paths, $method === 'reset' ? $options['pretend'] ?? false : $options);
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
