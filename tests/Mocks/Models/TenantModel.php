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

namespace Tenancy\Tests\Mocks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Tenancy\Affects\Connections\Support\Traits\OnTenant;
use Tenancy\Tests\Mocks\Models\Factories\TenantModelFactory;

class TenantModel extends Model
{
    use OnTenant;

    public $table = 'mocks';

    public static function factory(...$parameters)
    {
        if (function_exists('factory')) {
            return factory(get_called_class(), $parameters);
        }

        return App::make(TenantModelFactory::class)
                    ->count(is_numeric($parameters[0] ?? null) ? $parameters[0] : null)
                    ->state(is_array($parameters[0] ?? null) ? $parameters[0] : ($parameters[1] ?? []));
    }
}
