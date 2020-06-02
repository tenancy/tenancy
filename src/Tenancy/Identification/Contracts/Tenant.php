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

namespace Tenancy\Identification\Contracts;

interface Tenant
{
    /**
     * The attribute of the Model to use for the key.
     *
     * @return string
     */
    public function getTenantKeyName(): string;

    /**
     * The actual value of the key for the tenant Model.
     *
     * @return string|int
     */
    public function getTenantKey();

    /**
     * A unique identifier, eg class or table to distinguish this tenant Model.
     *
     * @return string
     */
    public function getTenantIdentifier(): string;
}
