<?php

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
