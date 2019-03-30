<?php

namespace Tenancy\Tests\Affects\Views;

use Illuminate\Contracts\View\Factory;
use Tenancy\Affects\Views\Events\ConfigureViews;
use Tenancy\Affects\Views\Providers\ServiceProvider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresViewsTest extends TestCase
{
    protected $additionalProviders = [ServiceProvider::class];
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
            $event->addNamespace(__DIR__ . '/views/');
        });

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();

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
            $event->addPath(__DIR__ . '/views/', true);
        });

        $this->resolveTenant($this->tenant);
        Tenancy::getTenant();

        $this->assertTrue($views->exists('test'));
    }
}