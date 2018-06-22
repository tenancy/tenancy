<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Eloquent;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use Tenancy\Database\DatabaseResolver;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Environment;

class ConnectionResolver implements ConnectionResolverInterface
{
    use Macroable;

    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var DatabaseResolver
     */
    private $resolver;
    /**
     * @var ConnectionResolverInterface|DatabaseManager
     */
    private $manager;
    /**
     * @var Dispatcher
     */
    private $events;

    public function __construct(
        DatabaseResolver $resolver,
        Environment $environment,
        DatabaseManager $manager,
        Dispatcher $events
    ) {
        $this->resolver    = $resolver;
        $this->environment = $environment;
        $this->manager     = $manager;
        $this->events      = $events;
    }

    /**
     * Get a database connection instance.
     *
     * @param  string $name
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection($name = null)
    {
        /** @var $tenant \Tenancy\Identification\Contracts\Tenant */
        if ($name === config('tenancy.database.tenant-connection-name') &&
            $tenant = $this->environment->getTenant() &&
            config("database.connections.$name.uuid") !== $tenant->getTenantKey() &&
            $provider = $this->resolver->__invoke($tenant, $name)) {
            $configuration = $provider->configure($tenant);

            Arr::set($configuration, 'uuid', $tenant->getTenantKey());

            $this->events->dispatch(new Configuring($name, $configuration, $provider));

            config(["database.connections.$name" => $configuration]);

            $this->manager->purge($name);
        }

        return $this->manager->connection($name);
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        if (config('tenancy.database.models-default-to-tenant-connection')) {
            return config('tenancy.database.tenant-connection-name');
        }

        return $this->manager->getDefaultConnection();
    }

    /**
     * Set the default connection name.
     *
     * @param  string $name
     * @return void
     */
    public function setDefaultConnection($name)
    {
        return $this->manager->setDefaultConnection($name);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->manager, $name], $arguments);
    }
}
