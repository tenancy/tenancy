<?php

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

namespace Tenancy\Eloquent\Connection;

use Tenancy\Environment;
use Tenancy\Identification\Contracts\Tenant;

trait OnSystem
{
    public function getConnectionName()
    {
        /** @var Tenant $tenant */
        $tenant = app(Tenant::class);
        return $tenant ? $tenant->getManagingSystemConnection() : Environment::getDefaultSystemConnectionName();
    }
}
