<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Database;

use Tenancy\Database\Contracts\ProvidesPassword;
use Tenancy\Identification\Contracts\Tenant;

class PasswordGenerator implements ProvidesPassword
{
    /**
     * @param Tenant $tenant
     *
     * @return string
     */
    public function generate(Tenant $tenant): string
    {
        return md5(sprintf(
            '%s-%s-%s',
            $tenant->getTenantIdentifier(),
            $tenant->getTenantKey(),
            config('tenancy.key') ?? config('app.key')
        ));
    }
}
