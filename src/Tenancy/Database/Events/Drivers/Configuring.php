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

namespace Tenancy\Database\Events\Drivers;

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Contracts\ProvidesPassword;
use Tenancy\Identification\Contracts\Tenant;

class Configuring
{
    /**
     * @var Tenant
     */
    private $tenant;
    /**
     * @var array
     */
    public $configuration;
    /**
     * @var ProvidesDatabase
     */
    public $provider;

    public function __construct(Tenant $tenant, array &$configuration, ProvidesDatabase $provider)
    {
        $configuration = $this->defaults($tenant, $configuration);

        $this->tenant = $tenant;
        $this->configuration = &$configuration;
        $this->provider = $provider;
    }

    public function useConnection(string $connection)
    {
        $this->configuration = config("database.connections.$connection");

        return $this;
    }

    protected function defaults(Tenant $tenant, array &$configuration): array
    {
        if ($tenant->isDirty($tenant->getTenantKeyName())) {
            $configuration['oldUsername'] = $tenant->getOriginal($tenant->getTenantKeyName());
        }

        $configuration['username'] = $configuration['username'] ?? $tenant->getTenantKey();
        $configuration['database'] = $configuration['database'] ?? $configuration['username'];
        $configuration['password'] = $configuration['password'] ?? resolve(ProvidesPassword::class)->generate($tenant);

        return $configuration;
    }
}
