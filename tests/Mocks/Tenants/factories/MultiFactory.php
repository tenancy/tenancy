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

use Faker\Generator as Faker;
use Tenancy\Tests\Mocks\Tenants\MysqlTenant;
use Tenancy\Tests\Mocks\Tenants\NullConsoleTenant;
use Tenancy\Tests\Mocks\Tenants\NullEnvironmentTenant;
use Tenancy\Tests\Mocks\Tenants\NullHttpTenant;
use Tenancy\Tests\Mocks\Tenants\NullMixedTenant;
use Tenancy\Tests\Mocks\Tenants\NullQueueTenant;
use Tenancy\Tests\Mocks\Tenants\SimpleConsoleTenant;
use Tenancy\Tests\Mocks\Tenants\SimpleQueueTenant;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
foreach ([
    MysqlTenant::class,
    NullConsoleTenant::class,
    NullEnvironmentTenant::class,
    NullHttpTenant::class,
    NullMixedTenant::class,
    NullQueueTenant::class,
    SimpleConsoleTenant::class,
    SimpleQueueTenant::class,
] as $tenant) {
    $factory->define($tenant, function (Faker $faker) {
        return [
            'id'             => $faker->unixTime,
            'name'           => $faker->name,
            'email'          => $faker->unique()->safeEmail,
            'password'       => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => $faker->slug(2),
        ];
    });
}
