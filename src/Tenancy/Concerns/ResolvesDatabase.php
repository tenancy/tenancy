<?php

namespace Tenancy\Concerns;

use Illuminate\Database\DatabaseManager;

trait ResolvesDatabase
{
    protected function db(): DatabaseManager
    {
        return resolve('db');
    }
}