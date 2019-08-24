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

namespace Tenancy\Hooks\Database\Support;

use Closure;
use Tenancy\Hooks\Database\Events\Resolving;
use Tenancy\Hooks\Database\Events\Drivers\Configuring;

trait InteractsWithDatabases{
    protected function resolveDatabase(Closure $callback){
        $this->events->listen(Resolving::class, $callback);
    }

    protected function configureDatabase(Closure $callback){
        $this->events->listen(Configuring::class, $callback);
    }
}
