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
use Tenancy\Tests\Mocks\Models\TenantModel;

$factory->define(TenantModel::class, function (Faker $faker) {
    return [
        'id'   => $faker->unixTime,
        'name' => $faker->name,
    ];
});
