<?php

namespace Tenancy\Tests\Database\Mysql\Mocks;

use Tenancy\Testing\Mocks;
use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;

class Tenant extends Mocks\Tenant implements ManagesSystemConnection{
    public function getManagingSystemConnection(): ?string
    {
        return "mysql";
    }
}
