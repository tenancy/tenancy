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

namespace Tenancy\Database\Contracts;

use Tenancy\Identification\Contracts\Tenant;

interface ProvidesDatabase
{
    /**
     * @param Tenant $tenant
     *
     * @return array
     */
    public function configure(Tenant $tenant): array;

    /**
     * @param Tenant $tenant
     *
     * @return bool
     */
    public function create(Tenant $tenant): bool;

    /**
     * @param Tenant $tenant
     *
     * @return bool
     */
    public function update(Tenant $tenant): bool;

    /**
     * @param Tenant $tenant
     *
     * @return bool
     */
    public function delete(Tenant $tenant): bool;
}
