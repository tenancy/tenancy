<?php

namespace Tenancy\Tests\Affects\Cache\Feature;

use Illuminate\Cache\ArrayStore;
use Illuminate\Support\Facades\Cache;
use Tenancy\Affects\Cache\Events\ConfigureCache;
use Tenancy\Affects\Cache\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\AffectShouldBeUndone;

class ConfigureCacheArrayTest extends AffectsFeatureTestCase
{
    use AffectShouldBeUndone;

    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureCache::class, function (ConfigureCache $event) {
            $event->config['driver'] = 'array';
        });
    }

    protected function afterIdentification(Tenant $tenant = null)
    {
        if($tenant){
            Cache::driver('tenant')->add('tenant_test', $tenant->getTenantKey());
        }
    }

    protected function isAffected(Tenant $tenant): bool
    {
        $tenant_key = null;

        try {
            $tenant_key = Cache::driver('tenant')->get('tenant_test');

            $this->assertInstanceOf(
                ArrayStore::class,
                Cache::driver('tenant'),
                "The tenant store is not the right type"
            );
        } catch (\Exception $exception) {

        }
        return $tenant_key === $tenant->getTenantKey();
    }
}
