<?php

namespace Tenancy\Tests\Affects\Configs\Feature;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Configs\Events\ConfigureConfig;
use Tenancy\Affects\Configs\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\Configs\ThroughConfig;

class ConfigureConfigThroughConfigTest extends AffectsFeatureTestCase
{
    use ThroughConfig;

    protected $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Repository */
        $repository = $this->app->make(Repository::class);

        return !is_null($repository->get('testing'));
    }
}
