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

namespace Tenancy\Tests\Framework\Identification\Mocks;

use InvalidArgumentException;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Testing\Mocks\Tenant;

class TenantIdentifiableByMany extends Tenant implements IdentifiesByTest, IdentifiesByEnvironment
{
    public function tenantIdentificationByTest(): ?\Tenancy\Identification\Contracts\Tenant
    {
        return $this->newQuery()->first();
    }

    public function tenantIdentificationByEnvironment(): ?\Tenancy\Identification\Contracts\Tenant
    {
        throw new InvalidArgumentException("Trying to identify the wrong contract");
        return null;
    }
}
