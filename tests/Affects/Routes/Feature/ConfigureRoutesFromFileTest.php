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

namespace Tenancy\Tests\Affects\Routes\Feature;

use Illuminate\Routing\Router;
use Tenancy\Affects\Routes\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\Routes\AddsFromFile;

class ConfigureRoutesFromFileTest extends AffectsFeatureTestCase
{
    use AddsFromFile;

    protected $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        return $router->has('test');
    }
}
