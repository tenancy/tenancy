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

namespace Tenancy\Support;

use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Support\Contracts\ProvidesPassword;

class PasswordGenerator implements ProvidesPassword
{
    public function __invoke(Tenant $tenant): string
    {
        return md5(sprintf(
            '%s-%s-%s',
            $tenant->getTenantIdentifier(),
            $tenant->getTenantKey(),
            config('tenancy.key') ?? config('app.key')
        ));
    }
}
