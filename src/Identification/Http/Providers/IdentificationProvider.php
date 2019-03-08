<?php declare(strict_types=1);

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

namespace Tenancy\Identification\Drivers\Http\Providers;

use Illuminate\Contracts\Http\Kernel;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Http\Middleware\EagerIdentification;
use Tenancy\Identification\Support\DriverProvider;

class IdentificationProvider extends DriverProvider
{
    protected $drivers = [
        IdentifiesByHttp::class => 'tenantIdentificationByHttp'
    ];

    protected $configs = [
        __DIR__ . '/../resources/config/identification-driver-http.php'
    ];

    public function register()
    {
        parent::register();

        $this->app->resolving(Kernel::class, function (Kernel $kernel) {
            if (config('identification-driver-http.eager')) {
                $kernel->prependMiddleware(EagerIdentification::class);
            }
        });
    }
}
