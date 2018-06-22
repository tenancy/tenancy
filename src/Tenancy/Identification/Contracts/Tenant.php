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
     * @return mixed
     */
    public function getTenantKey();

    /**
     * The value type of the key.
     *
     * @return string
     */
    public function getTenantKeyType(): string;

    /**
     * A unique identifier, eg class or table to distinguish this tenant Model.
     *
     * @return string
     */
    public function getTenantIdentifier(): string;

    /**
     * Allows overriding the system connection used for the tenant.
     *
     * @return null|string
     */
    public function getManagingSystemConnection(): ?string;
}
