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

namespace Tenancy\Identification\Drivers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tenancy\Environment;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;

class EagerIdentification
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var Environment $tenancy */
        $tenancy = resolve(Environment::class);

        $tenancy->identifyTenant(false, IdentifiesByHttp::class);

        return $next($request);
    }
}
