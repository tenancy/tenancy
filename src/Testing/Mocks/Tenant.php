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
}
