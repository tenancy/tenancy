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

namespace Tenancy;

use Illuminate\Support\Traits\Macroable;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Events\Switched;

class Environment
{
    use Concerns\DispatchesEvents,
        Concerns\ResolvesTenants,
        Macroable;

    /**
     * @var Tenant|null
     */
    protected $tenant;

    /**
     * Whether the tenant has been identified previously.
     *
     * @var bool
     */
    protected $identified = false;

    public function setTenant(Tenant $tenant = null)
    {
        $this->tenant = $tenant;

        $this->events()->dispatch(new Switched($tenant));

        if (!$this->identified) {
            $this->identified = true;
        }

        return $this;
    }

    public function getTenant(bool $refresh = false): ?Tenant
    {
        if (!$this->identified || $refresh) {
            $resolver = $this->tenantResolver();

            $this->setTenant($resolver());
        }

        return $this->tenant;
    }

    public function isIdentified(): bool
    {
        return $this->identified;
    }

    public function setIdentified(bool $identified)
    {
        $this->identified = $identified;

        return $this;
    }
}
