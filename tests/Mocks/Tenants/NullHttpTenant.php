<?php

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
