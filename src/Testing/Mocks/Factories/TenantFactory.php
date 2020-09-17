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

namespace Tenancy\Testing\Mocks\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tenancy\Testing\Mocks\Tenant;

class TenantFactory extends Factory
{
    protected static $namespace = 'Tenancy\\Testing\\Mocks\\Factories';

    protected $model = Tenant::class;

    public function definition()
    {
        return [
            'id'             => $this->faker->unixTime,
            'name'           => $this->faker->name,
            'email'          => $this->faker->unique()->safeEmail,
            'password'       => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => $this->faker->slug(2),
        ];
    }
}
