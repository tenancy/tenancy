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

namespace Tenancy\Identification;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use Tenancy\Concerns\DispatchesEvents;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Support\TenantModelCollection;

class TenantResolver implements ResolvesTenants
{
    use DispatchesEvents;
    use Macroable;

    /**
     * The tenant models.
     *
     * @var TenantModelCollection
     */
    protected $models;

    protected $drivers = [];

    public function __construct()
    {
        $this->models = new TenantModelCollection();

        $this->configure();
    }

    public function __invoke(string $contract = null): ?Tenant
    {
        /** @var Tenant|null $tenant */
        $tenant = $this->events()->until(new Events\Resolving($models = $this->getModels()));

        if (!$tenant && !is_null($contract)) {
            return $this->identifyByContract($contract);
        }

        if (!$tenant && count($this->drivers) > 0) {
            $tenant = $this->resolveFromDrivers($models);
        }

        if ($tenant) {
            $this->events()->dispatch(new Events\Identified($tenant));
        }

        // Provide a debug log entry when no tenant was identified, possibly because no identification driver is active.
        if (!$tenant && count($this->drivers) === 0) {
            logger('No tenant was identified, a possible cause being that no identification drivers are available.');
        }

        if (!$tenant) {
            $this->events()->dispatch(new Events\NothingIdentified($tenant));
        }

        $this->events()->dispatch(new Events\Resolved($tenant));

        return $tenant;
    }

    protected function configure()
    {
        $this->events()->dispatch(new Events\Configuring($this));
    }

    public function addModel(string $class)
    {
        if (!in_array(Tenant::class, class_implements($class))) {
            throw new InvalidArgumentException("$class has to implement ".Tenant::class);
        }

        $this->models->push($class);

        return $this;
    }

    public function getModels(): TenantModelCollection
    {
        return $this->models;
    }

    public function findModel(string $identifier, $key = null)
    {
        $model = $this->getModels()->map(function (string $model) {
            return new $model();
        })->first(function (Tenant $model) use ($identifier) {
            return $model->getTenantIdentifier() === $identifier;
        });

        if ($key !== null && $model) {
            return $model->where($model->getTenantKeyName(), $key)->first();
        }

        return $model;
    }

    /**
     * Updates the tenant model collection.
     *
     * @param TenantModelCollection $collection
     *
     * @return $this
     */
    public function setModels(TenantModelCollection $collection)
    {
        $this->models = $collection;

        return $this;
    }

    /**
     * @param string $contract
     *
     * @return $this
     */
    public function registerDriver(string $contract)
    {
        $this->drivers[] = $contract;

        return $this;
    }

    /**
     * @param TenantModelCollection $models
     *
     * @return Tenant
     */
    protected function resolveFromDrivers(TenantModelCollection $models): ?Tenant
    {
        $tenant = null;

        $models
            ->filterByContract($this->drivers)
            ->each(function (string $item) use (&$tenant) {
                $implements = class_implements($item);
                $drivers = array_intersect($implements, $this->drivers);

                foreach ($drivers as $driver) {
                    /** @var ReflectionMethod $method */
                    foreach ($this->retrieveDriverMethods($driver) as $method) {
                        try {
                            $tenant = app()->call("$item@{$method->getName()}");
                            // @codeCoverageIgnoreStart
                        } catch (BindingResolutionException $e) {
                            // @codeCoverageIgnoreEnd
                            // Prevent trying to find a tenant when bindings aren't working for them.
                        }

                        if ($tenant) {
                            return false;
                        }
                    }
                }
            });

        return $tenant;
    }

    /**
     * @param string $driver
     *
     * @return array|ReflectionMethod[]
     */
    protected function retrieveDriverMethods(string $driver): array
    {
        return (new ReflectionClass($driver))->getMethods(ReflectionMethod::IS_PUBLIC);
    }

    protected function identifyByContract(string $contract)
    {
        // Provide a debug log entry when no the specific identification driver has not been installed.
        if (!in_array($contract, $this->drivers)) {
            logger('Identification driver '.$contract.' was not available');

            return;
        }

        $tenant = $this->resolveFromDriver($this->getModels(), $contract);

        if ($tenant) {
            $this->events()->dispatch(new Events\Identified($tenant));
        }

        if (!$tenant) {
            $this->events()->dispatch(new Events\NothingIdentified($tenant));
        }

        $this->events()->dispatch(new Events\Resolved($tenant));

        return $tenant;
    }

    /**
     * @param TenantModelCollection $models
     * @param string|array          $contract
     *
     * @return Tenant|null
     */
    protected function resolveFromDriver(TenantModelCollection $models, string $contract): ?Tenant
    {
        $tenant = null;

        $methods = $this->retrieveDriverMethods($contract);

        $models
            ->filterByContract($contract)
            ->each(function (string $item) use (&$tenant, $methods) {
                foreach ($methods as $method) {
                    try {
                        $tenant = app()->call("$item@{$method->getName()}");
                        // @codeCoverageIgnoreStart
                    } catch (BindingResolutionException $e) {
                        // @codeCoverageIgnoreEnd
                        // Prevent trying to find a tenant when bindings aren't working for them.
                    }

                    if ($tenant) {
                        return false;
                    }
                }
            });

        return $tenant;
    }
}
