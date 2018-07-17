<?php

namespace Tenancy\Tests\Identification\Drivers\Http\Mocks;

use Tenancy\Identification\Concerns\AllowsTenantIdentification;
use Tenancy\Identification\Drivers\Http\Models\Hostname as HostnameAbstract;

class Hostname extends HostnameAbstract
{
    use AllowsTenantIdentification;
}
