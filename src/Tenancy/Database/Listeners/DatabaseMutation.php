<?php

namespace Tenancy\Database\Listeners;


use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Contracts\ResolvesConnections;
use Tenancy\Identification\Contracts\Tenant;

abstract class DatabaseMutation
{
    /**
     * @var ResolvesConnections
     */
    protected $resolver;

    public function __construct(ResolvesConnections $resolver)
    {
        $this->resolver = $resolver;
    }

    protected function driver(Tenant $tenant): ?ProvidesDatabase
    {
        $resolver = $this->resolver;

        return $resolver($tenant);
    }

    abstract public function handle($event): ?bool;
}