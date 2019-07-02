<?php

declare(strict_types=1);

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

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Identification\Contracts\Tenant;

class Resolved
{
    /**
     * @var Tenant|null
     */
    public $tenant;
    /**
     * @var ProvidesDatabase|null
     */
    public $provider;
    /**
     * @var string|null
     */
    public $connection;

    public function __construct(Tenant $tenant = null, string $connection = null, ProvidesDatabase &$provider = null)
    {
        $this->tenant = $tenant;
        $this->provider = &$provider;
        $this->connection = $connection;
    }
}
