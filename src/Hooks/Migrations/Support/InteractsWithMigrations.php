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

namespace Tenancy\Hooks\Migrations\Support;

use Tenancy\Hooks\Migrations\Events\ConfigureMigrations;

trait InteractsWithMigrations{

    protected function registerMigrationsPath(string $path){
        $this->events->listen(ConfigureMigrations::class, function (ConfigureMigrations $event) use ($path) {
            $event->path($path);
        });
    }
}
