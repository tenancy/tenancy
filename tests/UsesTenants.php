<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tenancy\Testing\Mocks\Factories\TenantFactory;
use Tenancy\Testing\Mocks\Tenant;

trait UsesTenants
{
    protected function registerFactories()
    {
        if (class_exists(\Illuminate\Database\Eloquent\Factory::class)) {
            /** @var \Illuminate\Database\Eloquent\Factory $factory */
            $factory = resolve(\Illuminate\Database\Eloquent\Factory::class);

            $factory->load(
                __DIR__.DIRECTORY_SEPARATOR.
                'Mocks'.DIRECTORY_SEPARATOR.
                'Tenants'.DIRECTORY_SEPARATOR.
                'Factories'.DIRECTORY_SEPARATOR.
                'Legacy'
            );
        }

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            if (is_subclass_of(new $modelName(), Tenant::class) || $modelName === Tenant::class) {
                return TenantFactory::class;
            }

            throw new \InvalidArgumentException('This is only meant to be used by Tenancy');
        });
    }
}
