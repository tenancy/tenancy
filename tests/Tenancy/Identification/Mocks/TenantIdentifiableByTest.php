<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Framework\Identification\Mocks;

use Tenancy\Testing\Mocks\Tenant;

class TenantIdentifiableByTest extends Tenant implements IdentifiesByTest
{
    public function tenantIdentificationByTest(): ?\Tenancy\Identification\Contracts\Tenant
    {
        return $this->newQuery()->first();
    }
}
