<?php

namespace Tenancy\Tests\Affects\Configs\Feature;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Configs\Events\ConfigureConfig;
use Tenancy\Affects\Configs\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;

class ConfigureConfigForwardTest extends AffectsFeatureTestCase
{
    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureConfig::class, function (ConfigureConfig $event) {
            $event->set('testing', 'tenancy');
        });
    }

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Repository */
        $repository = $this->app->make(Repository::class);

        return $repository->get('testing') === 'tenancy';
    }
}
