<?php

namespace Tenancy\Tests;

use Illuminate\Database\Eloquent\Factory;

trait UsesModels
{
    public function registerModelFactories()
    {
        /** @var Factory $factory */
        $factory = resolve(Factory::class);
        $factory->load(__DIR__ . DIRECTORY_SEPARATOR .
            'Mocks' . DIRECTORY_SEPARATOR .
            'Models' . DIRECTORY_SEPARATOR .
            'factories');
    }
}
