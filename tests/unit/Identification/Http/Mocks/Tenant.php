<?php

namespace Tenancy\Tests\Identification\Drivers\Http\Mocks;

use Illuminate\Http\Request;
use Tenancy\Identification\Contracts\Tenant as Contract;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;

class Tenant extends \Tenancy\Tests\Mocks\Tenant implements IdentifiesByHttp
{

    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param Request $request
     * @return Contract
     */
    public function tenantIdentificationByHttp(Request $request): ?Contract
    {
        return $this->newQuery()
            ->where('name', $request->path())
            ->first();
    }
}
