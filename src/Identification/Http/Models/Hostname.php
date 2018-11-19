<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

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
