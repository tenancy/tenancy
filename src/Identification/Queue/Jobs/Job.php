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

namespace Tenancy\Identification\Drivers\Queue\Jobs;

use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;
use ReflectionClass;
use Tenancy\Identification\Contracts\Tenant;

class Job
{
    use SerializesAndRestoresModelIdentifiers;

    protected ?Tenant $tenant = null;

    protected int|string|null $tenant_key = null;

    protected ?string $tenant_identifier = null;

    public function getTenant()
    {
        return $this->restoreValue($this->tenant);
    }

    public function getTenantIdentifier(): ?string
    {
        return $this->tenant_identifier;
    }

    public function getTenantKey(): string|int|null
    {
        return $this->tenant_key;
    }

    public function __unserialize(array $values)
    {
        $properties = (new ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            if (!in_array($property->getName(), ['tenant', 'tenant_identifier', 'tenant_key'])) {
                continue;
            }

            $name = $property->getName();

            if (!array_key_exists($name, $values)) {
                continue;
            }

            $property->setAccessible(true);

            $property->setValue($this, $this->restoreValue($values[$name]));
        }
    }

    protected function restoreValue($value): mixed
    {
        $value = unserialize(serialize($value));

        if ($value instanceof ModelIdentifier) {
            return $this->restoreModel($value);
        }

        return $value;
    }
}
