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
use Illuminate\Database\Eloquent\Factory;
use RuntimeException;
use Tenancy\Environment;
use Tenancy\Providers\TenancyProvider;

trait CreatesApplication
{
    protected $additionalProviders = [];

    protected $additionalMocks = [];

    protected $tenantModels = [];

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
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

    protected function bootTenancy()
    {
        $this->app->register(TenancyProvider::class);
        foreach ($this->additionalProviders as $provider) {
            $this->app->register($provider);
        }

        /** @var Factory $factory */
        $factory = resolve(Factory::class);
        $factory->load(__DIR__.'/../Mocks/factories/');

        $this->environment = resolve(Environment::class);
        $this->events = resolve(Dispatcher::class);
    }

    protected function tearDownTenancy()
    {
        // ..
    }
}
