<?php

namespace Tenancy\Tests;

use Illuminate\Database\Eloquent\Factory;

trait UsesTenants
{
    protected function registerFactories()
    {
        /** @var Factory $factory */
        $factory = resolve(Factory::class);
        $factory->load(__DIR__ . DIRECTORY_SEPARATOR .
            'Mocks' . DIRECTORY_SEPARATOR .
            'Tenants' . DIRECTORY_SEPARATOR .
            'factories');
    }
}
