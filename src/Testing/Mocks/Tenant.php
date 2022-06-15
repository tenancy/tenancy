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

namespace Tenancy\Testing\Mocks;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Tenancy\Identification\Concerns\AllowsTenantIdentification;
use Tenancy\Identification\Contracts\Tenant as Contract;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 */
class Tenant extends Model implements Contract
{
    protected $table = 'users';

    use AllowsTenantIdentification;

    public static function factory(...$parameters): Factory
    {
        if (function_exists('factory')) {
            return factory(get_called_class(), $parameters);
        }

        return Factory::factoryForModel(get_called_class())
                    ->count(is_numeric($parameters[0] ?? null) ? $parameters[0] : null)
                    ->state(is_array($parameters[0] ?? null) ? $parameters[0] : ($parameters[1] ?? []));
    }

    /**
     * Goes from the current class to a different class assuming they have the same key.
     *
     * @param string $class
     *
     * @return Model
     */
    public function as(string $class): Model
    {
        return $class::findOrFail($this->getKey());
    }
}
