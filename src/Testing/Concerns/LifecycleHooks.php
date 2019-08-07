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

namespace Tenancy\Testing\Concerns;

use Tenancy\Hooks\Migrations\Events\ConfigureMigrations;
use Tenancy\Hooks\Migrations\Events\ConfigureSeeds;

trait LifecycleHooks
{
    protected function seedTenant(string $path)
    {
        $this->events->listen(ConfigureSeeds::class, function (ConfigureSeeds $event) use ($path) {
            require_once $path;

            $seeder = basename($path, '.php');

            $event->seed($seeder);
        });
    }

    protected function migrateTenant(string $path)
    {
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) use ($path) {
            $event->path($path);
        });
    }
}
