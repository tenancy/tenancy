<?php

namespace Tenancy\Testing\Mocks;

use Illuminate\Database\Eloquent\Model;
use Tenancy\Eloquent\Connection\OnTenant;

class OnTenantModel extends Model
{
    use OnTenant;
}
