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

namespace Tenancy\Hooks\Migration\Support;

use Tenancy\Hooks\Migration\Events\ConfigureMigrations;
use Tenancy\Hooks\Migration\Events\ConfigureSeeds;

trait InteractsWithMigrations
{
    protected function registerMigrationsPath(string $path)
    {
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) use ($path) {
            $event->path($path);
        });
    }

    protected function registerSeederFile(string $path)
    {
        $this->events->listen(ConfigureSeeds::class, function (ConfigureSeeds $event) use ($path) {
            require_once $path;
            $seeder = basename($path, '.php');
            $event->seed($seeder);
        });
    }
}
