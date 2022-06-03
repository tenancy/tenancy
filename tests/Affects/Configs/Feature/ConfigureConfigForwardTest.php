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

namespace Tenancy\Tests\Affects\Configs\Feature;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Configs\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\Configs\ThroughForwarder;

class ConfigureConfigForwardTest extends AffectsFeatureTestCase
{
    use ThroughForwarder;

    protected array $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Repository */
        $repository = $this->app->make(Repository::class);

        return !is_null($repository->get('testing'));
    }
}
