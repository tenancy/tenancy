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

namespace Tenancy\Testing\Concerns;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Factories\Factory;
use RuntimeException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Tenancy\Environment;
use Tenancy\Providers\TenancyProvider;
use Tenancy\Testing\Mocks\Factories\TenantFactory;
use Tenancy\Testing\Mocks\Tenant;

trait CreatesApplication
{
    protected array $additionalProviders = [];

    protected array $additionalMocks = [];

    protected array $tenantModels = [];

    protected Environment $environment;

    protected Dispatcher $events;

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     */
    public function createApplication(): HttpKernelInterface
    {
        $appPaths = [];
        $app = null;

        // inside vendor
        $appPaths[] = realpath(__DIR__.'/../../framework/');
        // as a framework
        $appPaths[] = realpath(__DIR__.'/../../../vendor/laravel/laravel');

        foreach ($appPaths as $path) {
            $bootstrap = "$path/bootstrap/app.php";

            if (file_exists($bootstrap)) {
                $app = require $bootstrap;
                break;
            }
        }

        if (!$app) {
            throw new RuntimeException('No Laravel bootstrap.php file found, is laravel/laravel installed?');
        }

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function bootTenancy(): void
    {
        $this->app->register(TenancyProvider::class);
        foreach ($this->additionalProviders as $provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }

        $this->environment = resolve(Environment::class);
        $this->events = resolve(Dispatcher::class);

        $this->registerFactories();
    }

    protected function registerFactories()
    {
        if (class_exists(\Illuminate\Database\Eloquent\Factory::class)) {
            /** @var \Illuminate\Database\Eloquent\Factory */
            $factory = $this->app->make(\Illuminate\Database\Eloquent\Factory::class);
            $factory->load(__DIR__.'/../Mocks/Factories/Legacy');

            return;
        }

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            if (is_subclass_of(new $modelName(), Tenant::class) || $modelName === Tenant::class) {
                return TenantFactory::class;
            }

            throw new \InvalidArgumentException('This is only meant to be used by Tenancy');
        });
    }

    protected function tearDownTenancy()
    {
        // ..
    }
}
