<?php declare(strict_types=1);

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

namespace Tenancy;

use Illuminate\Database\Connection;
use Illuminate\Support\Traits\Macroable;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Events\Switched;

class Environment
{
    use Concerns\DispatchesEvents,
        Concerns\ResolvesDatabase,
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

        if (! $this->identified) {
            $this->identified = true;
        }

        return $this;
    }

    public function getTenant(bool $refresh = false): ?Tenant
    {
        if (! $this->identified || $refresh) {
            $resolver = $this->resolver();

            $this->setTenant($resolver());

            $this->identified = true;
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

    public function getTenantConnection(): ?Connection
    {
        return $this->db()->connection(static::getTenantConnectionName());
    }

    public static function getTenantConnectionName(): string
    {
        return config('tenancy.database.tenant-connection-name', 'tenant');
    }
}
