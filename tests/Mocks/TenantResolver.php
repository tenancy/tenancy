<?php

namespace Tenancy\Tests\Mocks;

use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Support\TenantModelCollection;

class TenantResolver implements ResolvesTenants
{
    /** @var array */
    public $drivers = [];

    public function __invoke(?string $contract = null): ?\Tenancy\Identification\Contracts\Tenant
    {
        return null;
    }

    public function addModel(string $class)
    {
        return $this;
    }

    public function findModel(string $identifier, $key = null)
    {
        return null;
    }

    public function setModels(\Tenancy\Identification\Support\TenantModelCollection $collection)
    {
        return $this;
    }

    public function getModels(): \Tenancy\Identification\Support\TenantModelCollection
    {
        return new TenantModelCollection();
    }

    public function registerDriver(string $contract)
    {
        $this->drivers[] = $contract;
    }
}
