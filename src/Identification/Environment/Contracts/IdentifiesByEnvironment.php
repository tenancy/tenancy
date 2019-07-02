<?php

declare(strict_types=1);

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

namespace Tenancy\Identification\Drivers\Environment\Contracts;

use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByEnvironment
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @return Tenant
     */
    public function tenantIdentificationByEnvironment(): ?Tenant;
}
