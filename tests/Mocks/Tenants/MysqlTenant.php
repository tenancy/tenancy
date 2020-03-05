<?php

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
