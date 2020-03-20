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

namespace Tenancy\Tests\Mocks\Tenants;

use Illuminate\Http\Request;
use Tenancy\Identification\Contracts\Tenant as TenantContract;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Testing\Mocks\Tenant;

class NullHttpTenant extends Tenant implements IdentifiesByHttp
{
    public function tenantIdentificationByHttp(Request $request): ?TenantContract
    {
        return null;
    }
}
