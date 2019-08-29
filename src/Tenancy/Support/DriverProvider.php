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

namespace Tenancy\Support;

use Tenancy\Identification\Contracts\ResolvesTenants;

abstract class DriverProvider extends Provider
{
    use Concerns\PublishesConfigs;
    /**
     * Identification driver registered by the Service Provider.
     *
     * @var array
     */
    protected $drivers = [];

    public function register()
    {
        parent::register();

        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            foreach ($this->drivers as $contract => $method) {
                $resolver->registerDriver($contract, $method);
            }
        });

        $this->publishConfigs();
    }
}
