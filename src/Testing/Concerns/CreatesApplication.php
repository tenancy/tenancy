<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
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

        if (getenv('CI_PROJECT_DIR')) {
            $appPaths[] = realpath(getenv('CI_PROJECT_DIR').'/vendor/laravel/laravel');
        }

        // inside vendor
        $appPaths[] = realpath(__DIR__.'/../../framework/');
        // as a framework
        $appPaths[] = realpath(__DIR__.'/../../../vendor/laravel/laravel');

        foreach ($appPaths as $path) {
            $bootstrap = "$path/bootstrap/app.php";

            if (file_exists($bootstrap)) {
                $this->injectServiceProvider($path);

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
        foreach ($this->additionalProviders as $provider) {
            $this->app->register($provider);
        }

        /** @var Factory $factory */
        $factory = resolve(Factory::class);
        $factory->load(__DIR__.'/../Mocks/factories/');

        foreach ($this->additionalMocks as $mock) {
            $factory->load($mock);
        }

        $this->environment = resolve(Environment::class);
        $this->events = resolve(Dispatcher::class);
    }

    protected function tearDownTenancy()
    {
        // ..
    }

    protected function createSystemTable(string $table, \Closure $callback)
    {
        $this->getConnection()->getSchemaBuilder()->create($table, $callback);
    }

    protected function injectServiceProvider(string $base)
    {
        $config = include "$base/config/app.php";

        if (!in_array(TenancyProvider::class, $config['providers'])) {
            array_unshift($config['providers'], TenancyProvider::class);

            file_put_contents("$base/config/app.php", sprintf('<?php return %s;', var_export($config, true)));
        }
    }
}
