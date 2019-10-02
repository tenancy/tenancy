<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Identification\Drivers\Queue\Middleware;

use Tenancy\Environment;

class SaveTenantOnQueuePayload
{
    public function __invoke(string $connection, string $queue = null, array $payload = [])
    {
        /** @var Environment $environment */
        $environment = resolve(Environment::class);
        $tenant = $environment->getTenant();

        return $tenant ? [
            'tenant_key'        => $tenant->getTenantKey(),
            'tenant_identifier' => $tenant->getTenantIdentifier(),
        ] : [];
    }
}
