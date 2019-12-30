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

namespace Tenancy\Identification\Drivers\Http\Middleware;

use Closure;
use Tenancy\Environment;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;

class EagerIdentification
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Environment $tenancy */
        $tenancy = resolve(Environment::class);

        if (!$tenancy->isIdentified()) {
            $tenancy->identifyTenant(false, IdentifiesByHttp::class);
        }

        return $next($request);
    }
}
