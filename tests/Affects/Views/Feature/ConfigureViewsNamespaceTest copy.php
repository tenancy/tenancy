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

namespace Tenancy\Tests\Affects\Views;

use Illuminate\Contracts\View\Factory;
use Tenancy\Affects\Views\Events\ConfigureViews;
use Tenancy\Affects\Views\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsFeatureTestCase;

class ConfigureViewsNamespaceTest extends AffectsFeatureTestCase
{
    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureViews::class, function (ConfigureViews $event) {
            $event->addNamespace($this->getViewsPath(), 'testing');
        });
    }

    protected function isAffected(Tenant $tenant): bool
    {
        /** @var Factory $views */
        $views = $this->app->make(Factory::class);

        return $views->exists('testing::test');
    }

    private function getViewsPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views';
    }
}
