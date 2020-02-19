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
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresViewsTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @var Tenant
     */
    protected $tenant;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * @test
     */
    public function adds_namespace()
    {
        /** @var Factory $views */
        $views = $this->app->make(Factory::class);
        $this->assertFalse($views->exists('tenant::test'));

        $this->events->listen(ConfigureViews::class, function (ConfigureViews $event) {
            $event->addNamespace(__DIR__.'/views/');
        });

        $this->resolveTenant($this->tenant);
        Tenancy::identifyTenant();

        $this->assertTrue($views->exists('tenant::test'));
    }

    /**
     * @test
     */
    public function replaces_root_namespace()
    {
        /** @var Factory $views */
        $views = $this->app->make(Factory::class);
        $this->assertFalse($views->exists('test'));

        $this->events->listen(ConfigureViews::class, function (ConfigureViews $event) {
            $event->addPath(__DIR__.'/views/', true);
        });

        $this->resolveTenant($this->tenant);
        Tenancy::identifyTenant();

        $this->assertTrue($views->exists('test'));
    }

    /**
     * @test
     */
    public function does_not_override()
    {
        /** @var Factory $views */
        $views = $this->app->make(Factory::class);
        $this->assertTrue($views->exists('welcome'));

        $original = $views->getFinder()->find('welcome');

        $this->events->listen(ConfigureViews::class, function (ConfigureViews $event) {
            $event->addPath(__DIR__.'/views/');
        });

        $this->resolveTenant($this->tenant);
        Tenancy::identifyTenant();

        $this->assertEquals(
            $original,
            $views->getFinder()->find('welcome')
        );
    }
}
