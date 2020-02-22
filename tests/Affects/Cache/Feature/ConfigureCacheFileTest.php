<?php

namespace Tenancy\Tests\Affects\Cache\Feature;

use Illuminate\Cache\FileStore;
use Illuminate\Support\Facades\Cache;
use Tenancy\Affects\Cache\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\AffectShouldBeUndone;
use Tenancy\Tests\Affects\Cache\UsesFileDriver;

class ConfigureCacheFileTest extends AffectsFeatureTestCase
{
    use UsesFileDriver;
    use AffectShouldBeUndone;

    protected $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        $result = false;

        try {
            $result = Cache::driver('tenant')->getStore() instanceof FileStore;
        } catch (\Exception $exception) {

        }
        return $result;
    }
}
