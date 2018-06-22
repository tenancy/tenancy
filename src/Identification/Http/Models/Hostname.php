<?php

namespace Tenancy\Identification\Drivers\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;

/**
 * Class Hostname
 *
 * @property int $id
 * @property string $fqdn
 *
 * @info Extend this model in your own code for easier http
 *       identification.
 */
abstract class Hostname extends Model implements IdentifiesByHttp, Tenant
{
    public function tenantIdentificationByHttp(Request $request): ?Tenant
    {
        return $this->newQuery()
            ->where('fqdn', $request->getHost())
            ->first();
    }
}
