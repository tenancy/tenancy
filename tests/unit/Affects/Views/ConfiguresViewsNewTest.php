<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
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
use Tenancy\Tests\Affects\AffectsTestCase;

class ConfiguresViewsNewTest extends AffectsTestCase
{
    /**
     * @var bool
     */
    protected $forwardCallTest = false;

    /**
     * @var bool
     */
    protected $undoTest = false;

    protected $additionalProviders = [Provider::class];

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureViews::class, function (ConfigureViews $event) {
            $event->addNamespace(__DIR__.'/views/');
        });
    }

    protected function assertAffected(Tenant $tenant)
    {
        /** @var Factory $views */
        $views = $this->app->make(Factory::class);
        $this->assertTrue($views->exists('tenant::test'));
    }

    protected function assertNotAffected(Tenant $tenant)
    {
        /** @var Factory $views */
        $views = $this->app->make(Factory::class);
        $this->assertFalse($views->exists('tenant::test'));
    }
}
