<?php

namespace Tenancy\Tests\Affects\Configs\Feature;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Configs\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\Configs\ThroughForwarder;

class ConfigureConfigForwardTest extends AffectsFeatureTestCase
{
    use ThroughForwarder;

    protected $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Repository */
        $repository = $this->app->make(Repository::class);

        return !is_null($repository->get('testing'));
    }
}
