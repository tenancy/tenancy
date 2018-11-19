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

namespace Tenancy\Identification\Drivers\Console\Contracts;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant;

interface IdentifiesByConsole
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param InputInterface $input
     * @return null|Tenant
     */
    public function tenantIdentificationByConsole(InputInterface $input): ?Tenant;
}
