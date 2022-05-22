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

namespace Tenancy\Identification\Concerns;

trait AllowsTenantIdentification
{
    public function getTenantKeyName(): string
    {
        return $this->getKeyName();
    }

    public function getTenantKey(): int|string
    {
        return $this->getKey();
    }

    public function getTenantIdentifier(): string
    {
        $identifier = $this->getTable();
        $connection = $this->getConnectionName() ?? config('database.default');

        return "$connection.$identifier";
    }
}
