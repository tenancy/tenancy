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

namespace Tenancy\Tests\Mocks\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tenancy\Tests\Mocks\Models\TenantModel;

class TenantModelFactory extends Factory
{
    protected $model = TenantModel::class;

    public function definition()
    {
        return [
            'id'   => $this->faker->unixTime,
            'name' => $this->faker->name,
        ];
    }
}
