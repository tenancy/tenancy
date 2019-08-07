<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Identification\Environment\Mocks;

use Tenancy\Identification\Contracts\Tenant as Contract;
use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;

class Tenant extends \Tenancy\Testing\Mocks\Tenant implements IdentifiesByEnvironment
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @return Contract
     */
    public function tenantIdentificationByEnvironment(): ?Contract
    {
        return $this->newQuery()
            ->where('name', env('TENANT_NAME'))
            ->first();
    }
}
