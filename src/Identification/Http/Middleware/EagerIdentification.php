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
use Tenancy\Environment;

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
            $tenancy->getTenant();
        }

        return $next($request);
    }
}
