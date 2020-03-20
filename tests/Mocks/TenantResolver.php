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
