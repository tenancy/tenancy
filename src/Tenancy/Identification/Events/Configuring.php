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

namespace Tenancy\Identification\Events;

use Tenancy\Identification\Contracts\ResolvesTenants;

class Configuring
{
    /**
     * @var ResolvesTenants
     */
    public $resolver;

    public function __construct(ResolvesTenants &$resolver)
    {
        $this->resolver = &$resolver;
    }
}
