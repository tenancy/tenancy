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

namespace Tenancy\Affects\Connection\Support;

use Closure;
use Tenancy\Affects\Connection\Events\Drivers\Configuring;
use Tenancy\Affects\Connection\Events\Resolving;

trait InteractsWithConnections
{
    protected function resolveConnection(Closure $callback)
    {
        $this->events->listen(Resolving::class, $callback);
    }

    protected function configureConnection(Closure $callback)
    {
        $this->events->listen(Configuring::class, $callback);
    }
}
