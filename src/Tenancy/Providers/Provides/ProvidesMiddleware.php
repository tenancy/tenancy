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

namespace Tenancy\Providers\Provides;

use Illuminate\Contracts\Http\Kernel;
use Tenancy\Identification\Middleware\EagerIdentification;

trait ProvidesMiddleware
{
    protected $middlewares = [
        // Configuration key, middleware mapping.
        'tenancy.identification.eager' => EagerIdentification::class,
    ];

    protected function registerProvidesMiddleware()
    {
        /** @var Kernel|\Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);

        foreach ($this->middlewares as $key => $middleware) {
            if (is_int($key) ||  (is_string($key) && config($key))) {
                $kernel->prependMiddleware($middleware);
            }
        }
    }
}
