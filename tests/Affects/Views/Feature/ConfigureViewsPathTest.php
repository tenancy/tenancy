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

namespace Tenancy\Tests\Affects\Views\Feature;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\ViewFinderInterface;
use Tenancy\Affects\Views\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;
use Tenancy\Tests\Affects\Views\AddsPaths;

class ConfigureViewsPathTest extends AffectsFeatureTestCase
{
    use AddsPaths;

    protected array $additionalProviders = [Provider::class];

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Factory $views */
        $views = $this->app->make(Factory::class);

        return $views->exists('test') && $this->app->make('view.finder') instanceof ViewFinderInterface;
    }
}
