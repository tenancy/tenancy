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

namespace Tenancy\Tests\Mocks\Tenants;

use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;
use Tenancy\Testing\Mocks\Tenant;

class MysqlTenant extends Tenant implements ManagesSystemConnection
{
    public function getManagingSystemConnection(): ?string
    {
        return 'mysql';
    }
}
