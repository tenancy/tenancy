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

namespace Tenancy\Affects\Connections\Events\Drivers;

use InvalidArgumentException;
use Tenancy\Affects\Connections\Contracts\ProvidesConfiguration;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Support\Contracts\ProvidesPassword;

class Configuring
{
    public function __construct(
        public Tenant $tenant,
        public array &$configuration,
        public ProvidesConfiguration $provider
    ) {
    }

    public function useConnection(string $connection, array $override = []): static
    {
        $this->configuration = array_merge(
            config("database.connections.$connection"),
            $override
        );

        return $this;
    }

    public function useConfig(string $path, array $override = []): static
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("Cannot set up tenant connection configuration, file $path does not exist.");
        }

        $this->configuration = array_merge(
            include $path,
            $override
        );

        return $this;
    }

    public function defaults(Tenant $tenant): array
    {
        $configuration = [];

        if ($tenant->isDirty($tenant->getTenantKeyName())) {
            $configuration['oldUsername'] = $tenant->getOriginal($tenant->getTenantKeyName());
        }

        $configuration['username'] = $tenant->getTenantKey();
        $configuration['database'] = $configuration['username'];
        $configuration['password'] = resolve(ProvidesPassword::class)->__invoke($tenant);

        return $configuration;
    }
}
