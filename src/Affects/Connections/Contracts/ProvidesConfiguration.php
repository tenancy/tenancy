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

namespace Tenancy\Affects\Connections\Contracts;

use Tenancy\Identification\Contracts\Tenant;

interface ProvidesConfiguration
{
    /**
     * @param Tenant $tenant
     *
     * @return array
     */
    public function configure(Tenant $tenant): array;
}
