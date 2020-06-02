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
