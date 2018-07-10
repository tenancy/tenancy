<?php

namespace Tenancy\Identification\Drivers\Http\Contracts;

use Illuminate\Http\Request;
use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByHttp
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param Request $request
     * @return Tenant
     */
    public static function tenantIdentificationByHttp(Request $request): ? Tenant;
}
