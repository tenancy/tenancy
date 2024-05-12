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

namespace Tenancy\Identification\Drivers\Http\Providers;

use Illuminate\Contracts\Http\Kernel;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Http\Middleware\EagerIdentification;
use Tenancy\Support\DriverProvider;

class IdentificationProvider extends DriverProvider
{
    protected array $drivers = [
        IdentifiesByHttp::class,
    ];

    protected array $configs = [
        __DIR__.'/../resources/config/identification-driver-http.php',
    ];

    public function register()
    {
        parent::register();

        $this->app->extend(Kernel::class, function (Kernel $kernel) {
            if (config('tenancy.identification-driver-http.eager')) {
                $kernel->prependMiddleware(EagerIdentification::class);
            }

            return $kernel;
        });
    }
}
