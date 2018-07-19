<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Database\Events;

use Tenancy\Identification\Contracts\Tenant;

class Resolving
{
    /**
     * @var Tenant|null
     */
    public $tenant;
    /**
     * @var string|null
     */
    public $connection;

    public function __construct(Tenant $tenant = null, string $connection = null)
    {
        $this->tenant = $tenant;
        $this->connection = $connection;
    }
}
