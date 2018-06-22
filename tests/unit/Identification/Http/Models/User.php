<?php

namespace Tenancy\Tests\Identification\Drivers\Http\Models;

use Illuminate\Http\Request;
use Tenancy\Identification\Concerns\AllowsTenantIdentification;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;

class User extends \App\User implements Tenant, IdentifiesByHttp
{
    use AllowsTenantIdentification;

    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param Request $request
     * @return bool
     */
    public function tenantIdentificationByHttp(Request $request): bool
    {
        return $this->name === $request->path();
    }
}
