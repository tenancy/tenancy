<?php

namespace Tenancy\Tests;

trait UsesSeeders
{
    public function getSeederPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR .
            'Mocks' . DIRECTORY_SEPARATOR .
            'Seeders' . DIRECTORY_SEPARATOR .
            'MockSeeder.php';
    }
}
