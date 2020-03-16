<?php

namespace Tenancy\Tests;

trait UsesMigrations
{
    public function getMigrationsPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR
            . 'Mocks' . DIRECTORY_SEPARATOR
            . 'Migrations';
    }
}
