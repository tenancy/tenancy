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
        $this->events->listen(ConfigureModels::class, function (ConfigureModels $event) {
            $event->setConnectionResolver(
                $this->model,
                new ConnectionResolver('sqlite', resolve('db'))
            );
        });
    }

    protected function isAffected(Tenant $tenant): bool
    {
        return (new $this->model())->getConnectionResolver() instanceof ConnectionResolver;
    }
}
