<?php

namespace Tenancy\Tests\Affects\Models\Feature;

use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Affects\Models\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Mocks\ConnectionResolver;
use Tenancy\Tests\Mocks\Models\SimpleModel;

class ConfigureModelsForwardResolverTest extends AffectsFeatureTestCase
{
    protected $additionalProviders = [Provider::class];

    protected $model = SimpleModel::class;

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event){
            $event->setConnectionResolver(
                $this->model,
                new ConnectionResolver('sqlite', resolve('db'))
            );
        });
    }

    protected function isAffected(Tenant $tenant): bool
    {
        return (new $this->model)->getConnectionResolver() instanceof ConnectionResolver;
    }
}
