<?php

namespace Tenancy\Testing\Mocks;

use Illuminate\Database\Eloquent\Model;
use Tenancy\Eloquent\Connection\OnSystem;

class OnSystemModel extends Model
{
    use OnSystem;
}
