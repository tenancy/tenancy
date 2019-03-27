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

namespace Tenancy\Database\Listeners;

use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Contracts\ResolvesConnections;
use Tenancy\Identification\Contracts\Tenant;

abstract class DatabaseMutation
{
    /**
     * @var ResolvesConnections
     */
    protected $resolver;

    public function __construct(ResolvesConnections $resolver)
    {
        $this->resolver = $resolver;
    }

    protected function driver(Tenant $tenant): ?ProvidesDatabase
    {
        $resolver = $this->resolver;

        return $resolver($tenant);
    }

    abstract public function handle($event): ?bool;
}
