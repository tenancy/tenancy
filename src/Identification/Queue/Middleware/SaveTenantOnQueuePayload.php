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

namespace Tenancy\Identification\Drivers\Queue\Middleware;

use Tenancy\Facades\Tenancy;

class SaveTenantOnQueuePayload
{
    public function __invoke(string $connection, string $queue = null, array $payload = []): array
    {
        $tenant = Tenancy::getTenant();

        return $tenant ? [
            'tenant_key'        => $tenant->getTenantKey(),
            'tenant_identifier' => $tenant->getTenantIdentifier(),
        ] : [];
    }
}
