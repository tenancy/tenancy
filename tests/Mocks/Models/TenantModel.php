<?php

namespace Tenancy\Tests\Mocks\Models;

use Illuminate\Database\Eloquent\Model;
use Tenancy\Affects\Connections\Support\Traits\OnTenant;

class TenantModel extends Model
{
    use OnTenant;

    public $table = 'mocks';
}
