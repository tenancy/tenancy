<?php

namespace Tenancy\Tests\Database\Mocks;

use Illuminate\Database\Eloquent\Model;
use Tenancy\Eloquent\Connection\OnTenant;

class Mock extends Model{
    use OnTenant;

}
