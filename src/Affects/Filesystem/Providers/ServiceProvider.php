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

namespace Tenancy\Affects\Filesystem\Providers;

use Tenancy\Affects\Filesystem\Listeners\ConfiguresDisk;
use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Support\DriverProvider;

class ServiceProvider extends DriverProvider
{
    protected $listen = [
        Resolved::class => [
            ConfiguresDisk::class
        ]
    ];
}
