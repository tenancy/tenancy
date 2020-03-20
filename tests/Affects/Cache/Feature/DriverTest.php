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

namespace Tenancy\Tests\Affects\Cache\Feature;

use Illuminate\Support\Facades\Cache;
use Tenancy\Affects\Cache\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\AffectShouldBeUndone;

abstract class DriverTest extends AffectsFeatureTestCase
{
    use AffectShouldBeUndone;

    protected $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        $result = false;

        try {
            $result = Cache::driver('tenant')->getStore() instanceof $this->storeClass;
        } catch (\Exception $exception) {
        }

        return $result;
    }
}
